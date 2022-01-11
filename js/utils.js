// General Util Functions
function generateUUID() { // Public Domain/MIT
	var d = new Date().getTime();//Timestamp
	var d2 = ((typeof performance !== 'undefined') && performance.now && (performance.now() * 1000)) || 0;//Time in microseconds since page-load or 0 if unsupported
	return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
		var r = Math.random() * 16;//random number between 0 and 16
		if (d > 0) {//Use timestamp until depleted
			r = (d + r) % 16 | 0;
			d = Math.floor(d / 16);
		} else {//Use microseconds since page-load if supported
			r = (d2 + r) % 16 | 0;
			d2 = Math.floor(d2 / 16);
		}
		return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
	});
}

function generateUniqueHospitalID(regdate, hcIdentifier){
	var uuidRandFourDigit = generateUUID().substring(2, 6);
	uuidRandFourDigit = uuidRandFourDigit.toUpperCase();

	const dateToday = new Date(regdate);
	var dateString = ('0' + dateToday.getFullYear()).slice(-2) + ('0' + (dateToday.getMonth() + 1)).slice(-2) + ('0' + (dateToday.getDate())).slice(-2);

	var uniqueRegID = dateString + hcIdentifier + uuidRandFourDigit
	return uniqueRegID;
}

// Validator Functions
$.validator.addMethod(
	"checkvalue",
	function (value, element) {
		return this.optional(element) || !(value == '');
	},
	"Values do not match!"
);

// Number to Words
function FeesInWords(num) {
	var a = ['', 'One ', 'Two ', 'Three ', 'Four ', 'Five ', 'Six ', 'Seven ', 'Eight ', 'Nine ', 'Ten ', 'Eleven ', 'Twelve ', 'Thirteen ', 'Fourteen ', 'Fifteen ', 'Sixteen ', 'Seventeen ', 'Eighteen ', 'Nineteen '];
	var b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
	if ((num = num.toString()).length > 9) return 'overflow';
	n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
	if (!n) return; var str = '';
	str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'Crore ' : '';
	str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'Lakh ' : '';
	str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'Thousand ' : '';
	str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'Hundred ' : '';
	str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + '' : '';
	return str;
}

// Print Process PDF
function processPDF(data) {
	window.jsPDF = window.jspdf.jsPDF;

	// Set Document Properties
	var doc = new jsPDF({
		orientation: "landscape",
		unit: "in",
		format: [5.8, 8.3]
	});

	doc.setFontSize(10);

	// Get Document Width and Height for Position Calculations
	var docWidth = doc.internal.pageSize.getWidth();
	var docHeight = doc.internal.pageSize.getHeight();

	// BG Image - Template
	doc.addImage("images/visit_receipt_template.png", "PNG", 0, 0, docWidth, docHeight);

	// Text Elements
	// Row 1
	doc.text(data[0].visit_id, 1.4, 2.1);
	doc.text(data[0].reg_no, 5.5, 2.1);

	// Row 2
	doc.text(data[0].consultation_mode, 2.15, 2.37);
	doc.text(data[0].doctor_name, 6.22, 2.37);

	// Row 3
	doc.text(data[0].payment_status, 1.93, 2.60);
	doc.text(data[0].payment_method, 3.9, 2.60);
	doc.text(data[0].remarks, 5.47, 2.60);

	// Row 4
	doc.text(data[0].p_fullname, 1.77, 3.05);
	doc.text(data[0].p_age, 4.15, 3.05);
	doc.text(data[0].p_gender, 5.2, 3.05);
	doc.text(data[0].date_of_visit, 6.3, 3.05);

	// Row 5
	doc.text(data[0].p_address, 1.45, 3.27);
	doc.text(data[0].p_pno, 5.4, 3.27);

	// Row 6
	doc.text("Consultation Fees", 0.8, 4);
	doc.text(data[0].consultation_fees, 6.4, 4);

	// Row 7
	doc.text("Rs. "+FeesInWords(data[0].consultation_fees)+"Only /-", 2.25, 4.83);


	// Render the PDF as a BLOB and Open Temporarily.
	var blobPDF = new Blob([doc.output("blob")], { type: 'application/pdf' });
	var blobUrl = URL.createObjectURL(blobPDF);

	// Open PDF in PDF Viewer
	window.open(blobUrl);
}