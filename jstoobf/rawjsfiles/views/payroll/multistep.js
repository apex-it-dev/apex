$(function() {

    //DOM elements
    const DOMstrings = {
      stepsBtnClass: 'multisteps-form__progress-btn',
      stepsBtns: document.querySelectorAll(`.multisteps-form__progress-btn`),
      stepsBar: document.querySelector('.multisteps-form__progress'),
      stepsForm: document.querySelector('.multisteps-form__form'),
      stepsFormTextareas: document.querySelectorAll('.multisteps-form__textarea'),
      stepFormPanelClass: 'multisteps-form__panel',
      stepFormPanels: document.querySelectorAll('.multisteps-form__panel'),
      stepPrevBtnClass: 'js-btn-prev',
      stepNextBtnClass: 'js-btn-next' };
      
      
      //remove class from a set of items
      const removeClasses = (elemSet, className) => {
        
        elemSet.forEach(elem => {
          
          elem.classList.remove(className);
          
        });
        
      };
      
      //return exect parent node of the element
      const findParent = (elem, parentClass) => {
        
        let currentNode = elem;
        
        while (!currentNode.classList.contains(parentClass)) {
          currentNode = currentNode.parentNode;
        }
        
        return currentNode;
        
      };
      
      //get active button step number
      const getActiveStep = elem => {
        return Array.from(DOMstrings.stepsBtns).indexOf(elem);
      };
      
      //set all steps before clicked (and clicked too) to active
      const setActiveStep = activeStepNum => {
        
        //remove active state from all the state
        removeClasses(DOMstrings.stepsBtns, 'js-active');
        
        //set picked items to active
        DOMstrings.stepsBtns.forEach((elem, index) => {
          
          if (index <= activeStepNum) {
            elem.classList.add('js-active');
          }
          
        });
      };
      
      //get active panel
      const getActivePanel = () => {
        
        let activePanel;
        
        DOMstrings.stepFormPanels.forEach(elem => {
          
          if (elem.classList.contains('js-active')) {
            
            activePanel = elem;
            
          }
          
        });
        
        return activePanel;
        
      };
      
      //open active panel (and close unactive panels)
      const setActivePanel = activePanelNum => {
        
        //remove active class from all the panels
        removeClasses(DOMstrings.stepFormPanels, 'js-active');
        
        //show active panel
        DOMstrings.stepFormPanels.forEach((elem, index) => {
          if (index === activePanelNum) {
            
            elem.classList.add('js-active');
            
            setFormHeight(elem);
            
          }
        });
        
      };
      
      //set form height equal to current panel height
      const formHeight = activePanel => {
        
        const activePanelHeight = activePanel.offsetHeight;
        
        DOMstrings.stepsForm.style.height = `${activePanelHeight}px`;
        
        // console.log(activePanelHeight);
        
      };
      
      const setFormHeight = () => {
        const activePanel = getActivePanel();
        
        formHeight(activePanel);
      };
      
      //STEPS BAR CLICK FUNCTION
      DOMstrings.stepsBar.addEventListener('click', e => {
        
        //check if click target is a step button
        const eventTarget = e.target;
        
        if (!eventTarget.classList.contains(`${DOMstrings.stepsBtnClass}`)) {
          return;
        }
        
        //get active button step number
        const activeStep = getActiveStep(eventTarget);
        
        //set all steps before clicked (and clicked too) to active
        setActiveStep(activeStep);
        
        //open active panel
        setActivePanel(activeStep);
      });
      
      //PREV/NEXT BTNS CLICK
      DOMstrings.stepsForm.addEventListener('click', e => {
        
        const eventTarget = e.target;
        
        //check if we clicked on `PREV` or NEXT` buttons
        if (!(eventTarget.classList.contains(`${DOMstrings.stepPrevBtnClass}`) || eventTarget.classList.contains(`${DOMstrings.stepNextBtnClass}`)))
        {
          return;
        }
        
        //find active panel
        const activePanel = findParent(eventTarget, `${DOMstrings.stepFormPanelClass}`);
        
        let activePanelNum = Array.from(DOMstrings.stepFormPanels).indexOf(activePanel);
        
        //set active step and active panel onclick
        if (eventTarget.classList.contains(`${DOMstrings.stepPrevBtnClass}`)) {
          activePanelNum--;
        } else {
          activePanelNum++;
        }
        // console.log(activePanelNum);
        
        panelNum = activePanelNum - 1;
        switch (panelNum) {
          case 0:
            category = 'grosssalary';
            updatePayrollDetails(category);
            setActiveStep(activePanelNum);
            setActivePanel(activePanelNum);
            break;
          
          case 1:
            category = 'salaryadjustment';
            updatePayrollDetails(category);
            setActiveStep(activePanelNum);
            setActivePanel(activePanelNum);
            break;
          case 2:
            category = 'govdeductions';
            var currentTabId = $('ul.nav-tabs li a.active').attr("id");
            if(currentTabId != 'summarygovdeduction-tab'){
              alertDialog('Please see the summary for government deduction before saving and continue.');
              return false;
            }else{
              updatePayrollDetails(category);
              setActiveStep(activePanelNum);
              setActivePanel(activePanelNum);
            }
            break;
          case 3:
            category = 'taxableincome';
            updatePayrollDetails(category);
            setActiveStep(activePanelNum);
            setActivePanel(activePanelNum);
            break;
           
          case 4:
            category = 'loans';
            updateLoanPaymentsToPayroll();
            setActiveStep(activePanelNum);
            setActivePanel(activePanelNum);
            break;
          case 5:
            category = 'miscellaneous';
            updateMiscToPayroll();
            setActiveStep(activePanelNum);
            setActivePanel(activePanelNum);
            break;
          default:
          break;
        }
        
      });
      
      //SETTING PROPER FORM HEIGHT ONLOAD
      window.addEventListener('load', setFormHeight, false);
      
      //SETTING PROPER FORM HEIGHT ONRESIZE
      window.addEventListener('resize', setFormHeight, false);
      
      //changing animation via animation select !!!YOU DON'T NEED THIS CODE (if you want to change animation type, just change form panels data-attr)
      
      const setAnimationType = newType => {
        DOMstrings.stepFormPanels.forEach(elem => {
          elem.dataset.animation = newType;
        });
      };
      
      //selector onchange - changing animation
      const animationSelect = document.querySelector('.pick-animation__select');
      
      animationSelect.addEventListener('change', () => {
        const newAnimationType = animationSelect.value;
        
        setAnimationType(newAnimationType);
      });

      $('#btnSubmit').on('click', function (e) {
        var msg = 'This payroll will be saved. Are you sure you want to submit?';
        var param = '';
        confirmDialog(msg, payrollCheckpoint,param);
    });
      
    });
    
    function updatePayrollDetails(category){
      var params = new window.URLSearchParams(window.location.search);
      var logfm = $("#logfm").html();
      var logto = $("#logto").html();
      var sesid = params.get('id');
      var office = atob(params.get('office'));
      var userid = $("#userid").val();
     
      const data = {
        userid: userid,
        category: category,
        office: office,
        logfm: logfm,
        logto: logto,
        sesid:sesid
      }
      blockUI(()=> {
          qryData('payroll', 'updatePayrollDetails', data, (data) => {
              // console.log(data);
              
              $.unblockUI(); 
          },true);
      });
    }
    
    function updateLoanPaymentsToPayroll(){
      const datatableid = '#loansviewtbl';
      let loanpaymentslist = $(datatableid).DataTable();
      let params = new window.URLSearchParams(window.location.search);
      let sesid = params.get('id');
      let userid = $("#userid").val();
      let result = [];
      let loanpayments = loanpaymentslist.data().toArray()
    
    let accumulatedlaonpayments = loanpayments.reduce((a, c) => {
        let filtered = a.filter(el => el.userid === c.userid);
        if(filtered.length > 0){
            a[a.indexOf(filtered[0])].amountdue += +c.amountdue;
        }else{
            a.push(c);
        }
        return a;
    }, []);
    
    // console.log(accumulatedlaonpayments);
      const data = {
        loanpayments : accumulatedlaonpayments,
        sesid : sesid,
        userid : userid
      } 
      
      blockUI(()=> {
        qryData('payroll', 'updateLoanPaymentsToPayroll', data, (data) => {
            // console.log(data);
            loadLoanDataTable();
            $.unblockUI(); 
        });
      });
    }
    
    function updateMiscToPayroll(){
      const datatableid = '#miscviewtbl';
      let miscellaneouspayments = $(datatableid).DataTable();
      let params = new window.URLSearchParams(window.location.search);
      let sesid = params.get('id');
      let userid = $("#userid").val();
      let miscpayments = miscellaneouspayments.data().toArray()
    
    var accumulatedmiscs = miscpayments.reduce((a, c) => {
        let filtered = a.filter(el => el.userid === c.userid);
        if(filtered.length > 0){
            a[a.indexOf(filtered[0])].miscamountfloatval += c.miscamountfloatval;
        }else{
            a.push(c);
        }
        return a;
    }, []);
    
    // console.log(accumulatedmiscs);
    // return false;
      const data = {
        miscpayments : accumulatedmiscs,
        sesid : sesid,
        userid : userid
      } 
      
      blockUI(()=> {
        qryData('payroll', 'updateMiscellaneousToPayroll', data, (data) => {
            genMiscellaneousPayments();
            genReviewPayroll();
            $.unblockUI(); 
            
        });
      });
    }

    function updateCalculatedNetPayableToPayroll(){
      const datatableid = '#reviewpayrollviewtbl';
      let payrollsummary = $(datatableid).DataTable();
      let payrollsummarylist = payrollsummary.data().toArray()
      let params = new window.URLSearchParams(window.location.search);
      let sesid = params.get('id');
      let userid = $("#userid").val();
  
      // console.log(payrollsummarylist);
      const data = {
          payrollsummarylist : payrollsummarylist,
          sesid : sesid,
          userid : userid
        } 
        
        blockUI(()=> {
          qryData('payroll', 'updateCalculatedNetPayableToPayroll', data, (data) => {
              $.unblockUI(); 
              
          }, true);
        });
  }

    