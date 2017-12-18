# Filter
A secure filter class to wrap access to variables from untrusted sources like `$_REQUEST` or `$_COOKIE`.

## Filter a value
The Filter::filter() method filters the given value and returns the sanitized result. If value is an `array`, the 
filter function is applied to all elements of the array, even recursive.

```
// Filter variable $text with Strip filter.
$text  = Filter::filter($text, "strip");
```

## Validate a value
The Filter::validate() method validates the given value and returns false if invalid. If value is an `array`, the 
validation check is applied to all elements of the array, the function will return false if any of the validated values 
inside the array is invalid.

```
// Check if variable $text does not contain any tags
$valid = Filter::validate($text, "strip");
```

## Using filter options
You can set special options for the used filter as the fourth method parameter. Available options can be seen below. 
Most filters use an array of $key => $value pairs to configure their behaviour.

```
$value = Filter::filter($value, $filter, ["Parameter" => "Value"]);
```

## Using multiple filters
You can use multiple filters and options in a queue and pass your value through them.
```
$value = Filter::filter($value, ["plain","strip","number], [ ["options" => "params"], ["options" => "params"], ["options" => "params"]]);
```

## Available Filters
You can specify any of the following filters for Filter. The name of the filter allways is 
identical to the first part of it's class name lowercase. (Example: "plain" means class "PlainFilter" in file 
"PlainFilter.php".

### Boolean
Filters a value and returns it as a boolean value of true or false

**Options**
- **true** an array of values that are treated as `true`. Default: `[ 1, "true", true ]`
- **strict** Only allow exactly the allowed values for the true option above and `false` if not. Otherwise a simple cast to boolean is also enough.

### DataTime
Validates or returns a formatted date-time string.

**Options**
- **format** the format to return datetime values in. Default: `Y-m-d H:i:s`
- **timezone** The timezone to use or `false` if none. Default: `false`

### Email
Validates or filters a email value. (No options)

### Enum
Allow only values from a fixed set of options.

**Options**
- **values** An array of allowed values. Default: `[]`
- **strict** Allowed values must also have the matching type. default: `false`
- **insensitive** Allow case insensitive checks for strings. Default `false`
- **autocorrect** Return the matching value from the allowed array on a successfull match (possibly correcting wrong types and cases). Default: `true`

### Json
Validate and optionally unserialize a inpout string with a JSON value.

**Options**
- **format** return the input in one of the following formats
    - *1* - JSON: Return untouched as the validated json string
    - *2* - DECODED: Return the decoded result as a multi dimensional array (not as stdClass objects)
    - *3* - SERIALIZED: return the input values as a php serialized string

### Money
This filter is a shorthand version of the number filter. It is preconfigured with defaults usable for money.
(See @Number for options)

### Number
Filter or validate numbers with or without decimals. Note: The result will be a `double`, regardless of decimal counts.

**Options**
- **min** Value has to be a minimum of X. Default: `false`
- **max** Value has to be a maximum of X. default: `false`
- **adjust** If a min/max is specified, allow the number to be sanitized to this if needed. Default `false`
- **decimals** Round the result to this number of decimals. Default: `false`
- **round** Method to use when rounding. One of round, floor or ceil. This method will use bc library for rounding to avoid floar errors. default: `round`
- **validate** Fail if value was not a valid Number as defined in options. default: `false`

### Passthrough
This filter allows any input without modification. Be carefull!

**Options**
- **append** append this fixed string to the input value. (This will fail validation though, as input differs from output.)

### Path
A shorthand version of the PlainExt filter, preconfigured to allow any character usable in a file path.

**Options**
- **characters** Additional valid characters apart from the ones from selected charactersets. Default: none
- **charactersets** Charactersets to use. A combination can be used by adding the numbers:
    - **1** - BASIC: `abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890._-`
    - **2** - WINDOWS: `\`
    - **4** - UNIT: `/`
    - **7** - ALL: all of the above

### PlainExt
A text filter, that allows certain characters, with preconfigured selectable charactersets.

**Options**
- **characters** Additional valid characters apart from the ones from selected charactersets. Default: none
- **charactersets** Charactersets to use. A combination can be used by adding the numbers:
    - **1** - BASIC: `abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890`
    - **2** - SIMPLE: `,.:-_()?! `
    - **4** - GERMAN: `äöüÄÖÜß`
    - **8** - FRENCH: `áéíóúàèìòùâêîôûÁÉÍÓÚÀÈÌÒÙÂÊÎÔÛ`
    - **13** - INTERNATIONAL: Basic + German + French
    - **15** - ALL: all of the above

### Plain

### Regexp

### Strip

### Url
