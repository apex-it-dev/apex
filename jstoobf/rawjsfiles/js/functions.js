/**
* @function qryData - Query data to server
* @param {string} api The file name of the API without .php extesion
* @param {string} f The function name from the API
* @param {object} d The data that will be passed to f
* @param {function} success A callback function that returns data
* @param {boolean} debug Enable debug mode, default: false
* @param {boolean} encrypt Enable encrytion, default: true
*/
function qryData(api = '', f = '', d = {}, success, debug = false, encrypt = false) {
  const url = getAPIURL() + api + '.php';
  const data = $.extend({'f': f}, d);

  let debugOut = {
      caller: qryData.caller.name,
      url: url,
      data: data
  }

  let getKey = () => {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: 'controllers/getkey.php',
        success: function(data){
          resolve(data);
        },
        error: function(request, status, err){
          reject(err);
        },
      });
    });
  }

  let ajaxCall = (data) => {
    $.ajax({
      type: 'POST',
      url: url,
      data: JSON.stringify({ data: data }),
      dataType: 'json',
      success: function(data) {
          debugOut = $.extend(debugOut, {successdata: data});
          if(debug) console.log(debugOut);

          success(data);
      },
      error: function(request, status, err) {
          debugOut = $.extend(debugOut,{
              errordata: {
                  error: err,
                  status: status,
                  request: request,
              }
          });
          if(debug) console.log(debugOut);
      }
    });
  };

  async function startEncrypt() {
    try {
      let key = await getKey();
      let jsEnc = new JSEncrypt({default_key_size: 3072});
      jsEnc.setKey(key);
      const newData = jsEnc.encrypt(JSON.stringify(data));
      debugOut = $.extend(debugOut, {
        key: key,
        encrypted: newData
      });
    
      ajaxCall(newData);
    } catch (err) {
      debugOut = $.extend(debugOut, {
        keyerror: err
      });
      ajaxCall(data);
    }
  };

  // start
  if(encrypt){
    startEncrypt();
  } else {
    ajaxCall(data);
  }
}


/* ---------------- start mobile detection ---------------- */
function mobileResponsive() {
  let deviceIsMobile = window.matchMedia('only screen and (max-width: 760px)').matches;
  if (deviceIsMobile) {
    $('#sidebarToggleTop').trigger('click');
  } else {
    $('#accordionSidebar')
      .addClass('toggled')
      .removeClass('toggled');
  }
  //   console.log(`Mobile mode: ${deviceIsMobile}`);
}

mobileResponsive();

// when window is resized
// issue: also triggers when clicking a textbox/inputfield
/* window.addEventListener('resize', function(event) {
  mobileResponsive();
});*/

/* ---------------- end mobile detection ---------------- */


function serverDateNow(callback) {
  // if(callback == undefined) {
  //   console.log('Callback should be set');
  //   return;
  // }
  $.ajax({
    url: 'inc/getdatetoday.php',
    success: function(data) {
      let newDate = new Date(data);
      if(callback != undefined) callback(newDate);
      return newDate;
    }
  });
}

function blockUI(onBlock, fadeIn = 1000) {
  $.blockUI({
    message: $('#preloader_image'),
    fadeIn: fadeIn,
    onBlock: function() {
      onBlock();
    }
  });
}

function checkImage(imagepath, result) {
  $.ajax({
    type: 'POST',
    url: 'controllers/check_image_exist.php',
    data: {img: imagepath},
    success: function(data) {
      result(data);
    },
    error: function(request, status, err) {
      result(false);
    }
  });
}

function getAccess(key) {
  return new Promise((resolve, reject) => {
    $.ajax({
      type:	'POST',
      url:	'controllers/getaccess.php',
      data:	{'key': key},
      success: function(data) {
        resolve(JSON.parse(data));
      },
      error: function(request, status, err) {
        reject(err);
      }
    });
  });
}