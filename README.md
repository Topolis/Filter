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

### Json

### Money

### Number

### Passthrough

### Path

### PlainExt

### Plain

### Regexp

### Strip

### Url
