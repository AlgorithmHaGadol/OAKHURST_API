<?php 

// Basic Enum Base
abstract class BasicEnum {
    private static $constCacheArray = NULL;

    private static function getConstants() {
        if (self::$constCacheArray == NULL) {
            self::$constCacheArray = [];
        }
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }
        return self::$constCacheArray[$calledClass];
    }

    public static function isValidName($name, $strict = false) {
        $constants = self::getConstants();

        if ($strict) {
            return array_key_exists($name, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($name), $keys);
    }

    public static function isValidValue($value, $strict = true) {
        $values = array_values(self::getConstants());
        return in_array($value, $values, $strict);
    }
}


// Defining The Enums

abstract class CancellationReasonCode extends BasicEnum {
    const Cheaper_Insurance = 'Cheaper_Insurance',
		Dissastisfied_With_Service = 'Dissastisfied_With_Service',
		Excess_Too_High = 'Excess_Too_High',
		Other = 'Other',
		Unhappy_With_Claims = 'Unhappy_With_Claims',
		Vehicle_Sold = 'Vehicle_Sold',
		Vehicle_Stolen = 'Vehicle_Stolen',
		Vehicle_Traded_In = 'Vehicle_Traded_In',
		Vehicle_Written_Off = 'Vehicle_Written_Off',
		Unaffordable = 'Unaffordable',
		No_Insurable_Pets = 'No_Insurable_Pets',
		Pet_Deceased = 'Pet_Deceased',
		Pet_Given_Away_or_Sold = 'Pet_Given_Away_or_Sold',
		Pet_Is_Missing = 'Pet_Is_Missing',
		Pet_Never_Collected = 'Pet_Never_Collected',
		Pet_Stolen = 'Pet_Stolen',
		Other_Insurance_Elsewhere = 'Other_Insurance_Elsewhere',
		Not_A_Medical_Aid = 'Not_A_Medical_Aid';
}

abstract class PlanType extends BasicEnum {
	const None = 'None',
		Accidental = 'Accidental',
		Standard = 'Standard',
		Superior = 'Superior',
		Basic = 'Basic',
		Exotic = 'Exotic',
		Senior = 'Senior',
		Emergency = 'Emergency',
		Basic_NZ = 'Basic_NZ',
		Classic = 'Classic',
		Deluxe = 'Deluxe';
}

abstract class PetType extends BasicEnum {
	const None = 'None',
		Dog = 'Dog',
		Cat = 'Cat',
		Bird_Prey = 'Bird_Prey',
		Reptile = 'Reptile',
		Small_Mammal = 'Small_Mammal',
		Exotic_Mammal = 'Exotic_Mammal',
		Small_Bird = 'Small_Bird',
		Large_Bird = 'Large_Bird',
		Turtle_Tortoise = 'Turtle_Tortoise',
		Bunny = 'Bunny';
}
abstract class BreedTypeCode extends PetType {}

abstract class GenderCde extends BasicEnum {
	const Male = 'Male', 
		Female = 'Female';
}
abstract class GenderCode extends GenderCde {}

abstract class RecordState extends BasicEnum {
	const None = 'None',
		Add = 'Add',
		Update = 'Update',
		Remove = 'Remove';
}
abstract class RecordSAction extends RecordState {}
abstract class RecordAction extends RecordState {}

abstract class StatusCode extends BasicEnum {
	const Active = 'Active',
		Cancelled = 'Cancelled',
		Pending = 'Pending';
}
abstract class PolicyStatusCode extends StatusCode {}
abstract class SectionStatusCode extends StatusCode {}

abstract class IDCode extends BasicEnum {
	const ID_SA = 'ID_SA',
		ID_Passport = 'ID_Passport',
		ID_None = 'ID_None',
		ID_Other = 'ID_Other';
}

abstract class MaritalCde extends BasicEnum {
	const Married = 'Married',
		Single_NeverMarried = 'Single_NeverMarried',
		Divorced = 'Divorced',
		Seperated = 'Seperated',
		Co_Habitating = 'Co_Habitating',
		Unknown = 'Unknown';
}
abstract class MaritalCode extends MaritalCde {}

abstract class OccupationCde extends BasicEnum {
	const Other = 'Other',
		Staff = 'Staff',
		Pensioner = 'Pensioner',
		SelfEmployed = 'SelfEmployed',
		Unemployed = 'Unemployed',
		Housewife = 'Housewife',
		Student = 'Student',
		PrivateSector = 'PrivateSector';
}

abstract class LanguageCode extends BasicEnum {
	const English = 'English',
		Afrikaans = 'Afrikaans',
		Xhosa = 'Xhosa',
		Ndebele = 'Ndebele',
		Zulu = 'Zulu',
		Northern_Sotho = 'Northern_Sotho',
		Sesotho = 'Sesotho',
		Tswana = 'Tswana',
		Swati = 'Swati',
		Venda = 'Venda',
		Tsonga = 'Tsonga',
		Unknown = 'Unknown';
}

abstract class TitleCode extends BasicEnum {
	const title_MR = 'title_MR',
		title_MRS = 'title_MRS',
		title_MISS = 'title_MISS',
		title_MS = 'title_MS',
		title_PROF = 'title_PROF',
		title_DR = 'title_DR',
		title_REF = 'title_REF',
		title_MANAGER = 'title_MANAGER',
		title_JUDGE = 'title_JUDGE';
}

abstract class AccountType extends BasicEnum {
	const None = 'None',
		Cheque = 'Cheque',
		Savings = 'Savings',
		Transmission = 'Transmission',
		DebitOrder = 'DebitOrder',
		Cash = 'Cash',
		CreditCard = 'CreditCard';
}

abstract class SaleTypeCode extends BasicEnum {
	const Live = 'Live',
		Automated = 'Automated',
		LiveCopy = 'LiveCopy';
}

abstract class Frequency extends BasicEnum {
	const None = 'None',
		Weekly = 'Weekly',
		Monthly = 'Monthly';
}

abstract class PaymentTypeCode extends Frequency {
	const Annually = 'Annually',
		OnceOff = 'OnceOff';
}
abstract class PaymentTypeCode2 extends PaymentTypeCode {}

abstract class PaymentMethodCode extends BasicEnum {
	const DebitOrder = 'DebitOrder',
		Cash = 'Cash';
}

abstract class SystemsArea extends BasicEnum {
	const Area_Policy = 'Area_Policy',
		Area_Claim = 'Area_Claim';
}

abstract class EducationCode extends BasicEnum {
	const No_Matric = 'No_Matric',
		Matric = 'Matric',
		Matric_Diploma = 'Matric_Diploma',
		Four_YrDiploma_Three_YrDegree = 'Four_YrDiploma_Three_YrDegree',
		Four_YrDegree = 'Four_YrDegree',
		Professional = 'Professional';
}

 ?>
