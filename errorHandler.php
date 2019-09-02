<?php 
include 'enumumeration.php';


class errorHandler {
	function __EnumErr(string $enum, $value, string $key) {
		$a = "<b>WARNING : </b> ".$key." is set to an Invalid Enum [".$value."]... Please use one of the following: <b>";
			$b = new ReflectionClass($enum);
			$c = $b->getConstants();
			foreach ($c as $id) {
				$a .= $id.' , ';
			}
			$a .= '</b>';
			$error[] = $a;
		return $a;
	}
	function quote(array $arr) {
		$error = array();
		// Insured Section
		!IDCode::isValidValue($arr['Insured']['IDType']) ? $error[] = self::__EnumErr('IDCode',$arr['Insured']['IDType'],'IDType') : '';
		!OccupationCde::isValidValue($arr['Insured']['OccupationCde']) ? $error[] = self::__EnumErr('OccupationCde',$arr['Insured']['OccupationCde'],'OccupationCde') : '';
		!GenderCde::isValidValue($arr['Insured']['GenderCde']) ? $error[] = self::__EnumErr('GenderCde',$arr['Insured']['GenderCde'],'GenderCde') : '';
		!MaritalCde::isValidValue($arr['Insured']['MaritalCde']) ? $error[] = self::__EnumErr('MaritalCde',$arr['Insured']['MaritalCde'],'MaritalCde') : '';
		!CustTypeCode::isValidValue($arr['Insured']['CustomerType']) ? $error[] = self::__EnumErr('CustTypeCode',$arr['Insured']['CustomerType'],'CustomerType') : '';
		!LanguageCode::isValidValue($arr['Insured']['Language']) ? $error[] = self::__EnumErr('LanguageCode',$arr['Insured']['Language'],'Language') : '';
		!TitleCode::isValidValue($arr['Insured']['Title']) ? $error[] = self::__EnumErr('TitleCode',$arr['Insured']['Title'],'Title') : '';
		!EducationCode::isValidValue($arr['Insured']['HighestEducation']) ? $error[] = self::__EnumErr('EducationCode',$arr['Insured']['HighestEducation'],'HighestEducation') : '';
		!RecordAction::isValidValue($arr['Insured']['RecordAction']) ? $error[] = self::__EnumErr('RecordAction',$arr['Insured']['RecordAction'],'RecordAction') : '';
		
		// Pet Section 
		
		!PlanType::isValidValue($arr['Pet']['PlanCode']) ? $error[] = self::__EnumErr('PlanType',$arr['Pet']['PlanCode'],'PlanCode') : 'PlanCode';
		!PaymentTypeCode::isValidValue($arr['Pet']['PaymentFrequency']) ? $error[] = self::__EnumErr('PaymentTypeCode',$arr['Pet']['PaymentFrequency'],'PaymentFrequency') : '';

		return $error;
	}
	function policy(array $arr) {
		$error = array();
		!SaleTypeCode::isValidValue($arr['SaleType']) ? $error[] = self::__EnumErr('SaleTypeCode',$arr['SaleType'],'SaleType') : '';
		!PaymentTypeCode::isValidValue($arr['PaymentFrequency']) ? $error[] = self::__EnumErr('PaymentTypeCode',$arr['PaymentFrequency'],'PaymentFrequency') : '';
		!PaymentMethodCode::isValidValue($arr['PaymentMethod']) ? $error[] = self::__EnumErr('PaymentMethodCode',$arr['PaymentMethod'],'PaymentMethod') : '';

		// InsuredDetail Section
		!IDCode::isValidValue($arr['InsuredDetail']['IDType']) ? $error[] = self::__EnumErr('IDCode',$arr['InsuredDetail']['IDType'],'IDType') : '';
		!GenderCde::isValidValue($arr['InsuredDetail']['GenderCde']) ? $error[] = self::__EnumErr('GenderCde',$arr['InsuredDetail']['GenderCde'],'GenderCde') : '';
		!MaritalCde::isValidValue($arr['InsuredDetail']['MaritalCde']) ? $error[] = self::__EnumErr('MaritalCde',$arr['InsuredDetail']['MaritalCde'],'MaritalCde') : '';
		!CustTypeCode::isValidValue($arr['InsuredDetail']['CustomerType']) ? $error[] = self::__EnumErr('CustTypeCode',$arr['InsuredDetail']['CustomerType'],'CustomerType') : '';
		!LanguageCode::isValidValue($arr['InsuredDetail']['Language']) ? $error[] = self::__EnumErr('LanguageCode',$arr['InsuredDetail']['Language'],'Language') : '';
		!TitleCode::isValidValue($arr['InsuredDetail']['Title']) ? $error[] = self::__EnumErr('TitleCode',$arr['InsuredDetail']['Title'],'Title') : '';
		!EducationCode::isValidValue($arr['InsuredDetail']['HighestEducation']) ? $error[] = self::__EnumErr('EducationCode',$arr['InsuredDetail']['HighestEducation'],'HighestEducation') : '';
		!RecordAction::isValidValue($arr['InsuredDetail']['RecordAction']) ? $error[] = self::__EnumErr('RecordAction',$arr['InsuredDetail']['RecordAction'],'RecordAction') : '';

		// Pets Section 
		
		foreach ($arr['Pets'] as $key => $pet) {
			!PlanType::isValidValue($pet['PlanCode']) ? $error[] = self::__EnumErr('PlanType',$pet['PlanCode'],'PlanCode in Pets['.$key.']') : '';
			!PetType::isValidValue($pet['TypeCode']) ? $error[] = self::__EnumErr('PetType',$pet['TypeCode'],'TypeCode in Pets['.$key.']') : '';
			!GenderCde::isValidValue($pet['Gender']) ? $error[] = self::__EnumErr('GenderCde',$pet['Gender'],'Gender in Pets['.$key.']') : '';
			!RecordState::isValidValue($pet['RecordAction']) ? $error[] = self::__EnumErr('RecordState',$pet['RecordAction'],'RecordAction in Pets['.$key.']') : '';
			!StatusCode::isValidValue($pet['Status']) ? $error[] = self::__EnumErr('StatusCode',$pet['Status'],'Status in Pets['.$key.']') : '';
		}

		return $error;
	}
}
