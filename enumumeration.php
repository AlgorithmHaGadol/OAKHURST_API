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
		Deluxe = 'Deluxe',
}


 ?>
