<?php
$snippetName='FormItBuilder_MyContactForm';
require_once $modx->getOption('core_path',null,MODX_CORE_PATH).'components/formitbuilder/model/formitbuilder/FormItBuilder.class.php';
if (function_exists('FormItBuilder_MyContactForm')===false) {
function FormItBuilder_MyContactForm(modX &$modx, string $snippetName) {	


/*--------------------*/
/*CREATE FORM ELEMENTS*/
/*--------------------*/
//Text Fields
$o_fe_name			= new FormItBuilder_elementText('name_full','Full Name');
$o_fe_age			= new FormItBuilder_elementText('age','Age');
$o_fe_username		= new FormItBuilder_elementText('username','Username');
$o_fe_userPass		= new FormItBuilder_elementPassword('user_pass','Password');
$o_fe_userPass2		= new FormItBuilder_elementPassword('user_pass2','Confirm Password');
$o_fe_address		= new FormItBuilder_elementText('address','Address');
$o_fe_city			= new FormItBuilder_elementText('city','City/Suburb');
$o_fe_postcode		= new FormItBuilder_elementText('postcode','Post Code');
$o_fe_company		= new FormItBuilder_elementText('company','Company Name');
$o_fe_companyPhone	= new FormItBuilder_elementText('company_phone','Company Phone');
$o_fe_email			= new FormItBuilder_elementText('email_address','Email Address');
//Check Boxes
$o_fe_checkTerms	= new FormItBuilder_elementCheckbox('agree_terms','I agree to the terms & conditions', 'Agree', 'Disagree', false);
$o_fe_checkNews		= new FormItBuilder_elementCheckbox('agree_newsletter','Sign me up for some spam', 'Wants Spam', 'Does <strong>NOT</strong> want spam', false);
//Dropdown selects
$a_employees=array(
	'10'=>'Less than 10',
	'11 to 20'=>'11 to 20',
	'50'=>'21 to 50',
	'100'=>'51 to 100',
	'100+'=>'More than 100',
);
$o_fe_employees		= new FormItBuilder_elementSelect('employees','Number of Employees',$a_employees,'11 to 20');
$a_usstates = array(
	''=>'Please select...',
	'AL'=>'Alabama',
	'AK'=>'Alaska',
	'AZ'=>'Arizona',
	'AR'=>'Arkansas',
	'CA'=>'California',
	'CO'=>'Colorado',  
	'CT'=>'Connecticut',
);
$o_fe_usstates		= new FormItBuilder_elementSelect('ussuate','Select a state',$a_usstates);
//radio groups
$a_performanceOptions = array(
	'opt1'=>'Poor',
	'opt2'=>'Needs Improvement',
	'opt3'=>'Average',
	'opt4'=>'Good',
	'opt5'=>'Excellent',
);
$o_fe_staff			= new FormItBuilder_elementRadioGroup('staff_performance','How would you rate staff performance?',$a_performanceOptions);
//Text area
$o_fe_notes			= new FormItBuilder_elementTextArea('notes','Additional Comments',5,30,
'Here is an example of default multiline text.

--- FormItBuilder ---
');
//Form Buttons
$o_fe_buttSubmit	= new FormItBuilder_elementButton('submit','Submit Form','submit');
$o_fe_buttReset		= new FormItBuilder_elementButton('reset','Reset Form','reset');


/*--------------------*/
/*SET VALIDATION RULES*/
/*--------------------*/
$a_formRules=array();
//Set required fields
$a_formFields_required = array($o_fe_notes, $o_fe_name, $o_fe_age, $o_fe_username, $o_fe_userPass, $o_fe_userPass2, $o_fe_email, $o_fe_postcode);
foreach($a_formFields_required as $field){
	$a_formRules[] = new FormRule(FormRuleType::required,$field);
}
$a_formRules[] = new FormRule(FormRuleType::email, $o_fe_email, NULL, 'Please provide a valid email address');
$a_formRules[] = new FormRule(FormRuleType::numeric, $o_fe_postcode);
$a_formRules[] = new FormRule(FormRuleType::required, $o_fe_checkTerms, NULL, 'You must agree to the terms and conditions');
$a_formRules[] = new FormRule(FormRuleType::required, $o_fe_staff, NULL, 'Please select an option for staff performance');

$a_formRules[] = new FormRule(FormRuleType::minimumLength, $o_fe_postcode, 4);
$a_formRules[] = new FormRule(FormRuleType::maximumLength, $o_fe_postcode, 4);
$a_formRules[] = new FormRule(FormRuleType::minimumLength, $o_fe_username, 6);
$a_formRules[] = new FormRule(FormRuleType::maximumLength, $o_fe_username, 30);
$a_formRules[] = new FormRule(FormRuleType::minimumValue, $o_fe_age, 18);
$a_formRules[] = new FormRule(FormRuleType::maximumValue, $o_fe_age, 100);
//A unique case, when checking if passwords match pass the two fields as an array into the second argument.
$a_formRules[] = new FormRule(FormRuleType::fieldMatch, array($o_fe_userPass2,$o_fe_userPass), NULL, 'Passwords do not match');

/*----------------------------*/
/*CREATE FORM AND ADD ELEMENTS*/
/*----------------------------*/
$o_form = new FormItBuilder($modx,'myContactForm');
$o_form->setHooks(array('spam','email','redirect'));
$o_form->setRedirectDocument(true);
$o_form->addRules($a_formRules);
$o_form->setPostHookName($snippetName);
$o_form->setEmailToAddress('marcus@datawebnet.com.au');
$o_form->setEmailFromAddress('[[+email_address]]');
$o_form->setEmailSubject('MyCompany Contact Form Submission - From: [[+name_full]]');
$o_form->setEmailHeadHtml('<p>This is a response sent by [[+name_full]] using the contact us form:</p>');
$o_form->setJqueryValidation(true);

//add elements to form in preferred order
$o_form->addElements(
	array(
		new FormItBuilder_htmlBlock('<h2>Personal Information</h2>'),
		$o_fe_name,	$o_fe_age,	$o_fe_username,	$o_fe_userPass,	$o_fe_userPass2, $o_fe_email,
		new FormItBuilder_htmlBlock('<hr class="formSpltter" /><h2>Address</h2>'),
		$o_fe_address,	$o_fe_city,	$o_fe_usstates, $o_fe_postcode,
		new FormItBuilder_htmlBlock('<hr class="formSpltter" /><h2>Company Information</h2>'),
		$o_fe_company,	$o_fe_companyPhone, $o_fe_employees, $o_fe_staff, $o_fe_notes,
		new FormItBuilder_htmlBlock('<hr class="formSpltter" /><div class="checkboxes">'),
		$o_fe_checkNews, $o_fe_checkTerms,
		new FormItBuilder_htmlBlock('</div>'),
		$o_fe_buttSubmit,	$o_fe_buttReset
	)
);

return $o_form;
	


}
}

//Run the form construction function above
$o_form = FormItBuilder_myContactForm($modx,$snippetName);
if(isset($outputType)===false){
	//this same snippet was called via the email posthook
	$hook->setValue('FormItBuilderEmailTpl',$o_form->postHook());
	return true;
}else{
	//Final output for form
	return $o_form->output();
}
?>