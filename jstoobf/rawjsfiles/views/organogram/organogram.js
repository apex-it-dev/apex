$(function() { 
    blockUI(() => {
        loadOrganogram();
    });
});

function loadOrganogram(){
	const passedData = {
		userid: $('#userid').val(),
		profilegroup: $('#profilegroup').val()
	};

	qryData('contactlist', 'getOrgChart', passedData, (data) => {
        const orgdata = data.orgchartdata;

        let myTemplate = OrgChart.templates;
        myTemplate.abacare = Object.assign({}, OrgChart.templates.ula, generateTemplate());

        
        const chart = new OrgChart($('#organogram')[0], {
            template: "abacare",
            enableDragDrop: false,
            collapse: {
                level: 1,
                allChildren: true
            },
            tags: {
                na: {
                    group: true,
                    groupName: "Unassigned",
                    template: "group_orange"
                }
            },
            menu: {
                    pdf: { text: "Export PDF" },
                    png: { text: "Export PNG" },
                    csv: { text: "Export CSV" },
                    svg: { text: "Export SVG" },
            },
            nodeBinding: {
                    field_0: "name",
                    field_1: "title",
                    field_2: "ini",
                    field_3: "office",
                    img_0: "img"
            },
            nodes: orgdata
        });
        $.unblockUI();
	});
}
 
function generateTemplate() {
    let myTemplate = {};
    
    myTemplate.field_0 = '<text width="145" class="field_0" style="font-size: 18px;" fill="#e74a3b" x="100" y="55">{val}</text>';
    myTemplate.minus = '<circle cx="15" cy="15" r="15" fill="#ffffff" stroke="#aeaeae" stroke-width="1"></circle><line x1="4" y1="15" x2="26" y2="15" stroke-width="1" stroke="#000000"></line>';
    myTemplate.plus = '<circle cx="15" cy="15" r="15" fill="#ffffff" stroke="#aeaeae" stroke-width="1"></circle><line x1="4" y1="15" x2="26" y2="15" stroke-width="1" stroke="#000000"></line><line x1="15" y1="4" x2="15" y2="26" stroke-width="1" stroke="#000000"></line>';
    myTemplate.node = '<rect x="0" y="0" height="120" width="250" fill="#ffffff" stroke-width="1" stroke="#aeaeae"></rect><line x1="0" y1="0" x2="250" y2="0" stroke-width="3" stroke="#e74a3b"></line>';
    myTemplate.link = '<path stroke-linejoin="round" stroke="#000000" stroke-width="1px" fill="none" d="M{xa},{ya} {xb},{yb} {xc},{yc} L{xd},{yd}" />';
    

    return myTemplate;
}