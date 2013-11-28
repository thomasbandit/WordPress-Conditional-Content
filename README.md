WordPress Conditional Content
=================

A simple but powerful WordPress plugin that allows you to use shortcodes as if statements in your content to display text only if certain conditions are met.

## How to use
You can place conditional content between `if` [shortcodes](http://codex.wordpress.org/Shortcode), and use its attributes to define conditions. The content between the shortcodes will only be displayed if all defined conditions are met.

### Defining conditions

Conditions are defined by setting the attributes of the shortcode in the following format:

	[if <type>=<condition>]
	 Conditional content
	[/if]
	
A basic example:

	[if qs="product-type:shoes"]
	 Thank you for buying shoes
	[/if]
	
The text in this example will only be displayed if the current URL has a GET paramater of "product-type" with the value "shoes".
	
Available condition types are:

* **`qs`** - Match on the key/value pair of the defined query string/GET parameter. Query string conditions are formatted like this:
 
		[if qs="<qskey>:<qsvalue>"] … [/if]

	`qskey` is the name of the query string variable and `qsvalue` the value to test for. For example, the condition `qs="product-type:2"` will match `?product-type=2`.

* **`referrer`** - To check on the current referrer. The condition will be true if `HTTP_REFERER` contains `<value>`. The match doesn't need to be exact, so if a user arrives from google and `<value>` is set to "google.com" the condition will be true.

* **`role`** - Matches the current user's role. The match needs to be exact so "admin" will not match "administrator". Use `role` with and empty value if you want to match users that are not logged in.

 
### Some examples:

Display content based on query string:

	[if qs="utm_source:partner-site"]
	 This content is only displayed if the current URL contains a GET paramater 'utm_source' with value 'partner-site'
	[/if]

Display content based on referrer:

	[if referrer="www.google.com"]
	 This content is only displayed if the referring URL contains 'www.google.com'
	[/if]

Display content based on user role:

	[if role="editor"]
	 This content is only displayed if the user is logged in with the role 'editor'
	[/if]
	
Setting multiple conditions and combining condition types:

	[if referrer="www.example.com" qs="utm_source:partner-site"]
		This content is displayed to users coming from example.com who clicked on a link originating from our RSS feed.
	[/if]
	
### Matching with `exact` or `contain`

By default query string conditions are matched on the exact value as defined in the shortcode. For looser 'wildcard' matching you can add a `match` attribute with the value `contain`:

	[if qs="product-type:cashmere-" match="contain"]
	 Good choice! Cashmere is a wonderful fabric.
	[/if]
	

This will match both `?product-type=cashmere-sweater` and `?product-type=cashmere-coat`
	
### Matching on multiple values
You can have a condition match on multiple values by using the semicolon as a seperator when defining allowed values. Example:

	[if qs="product-type:shoes;coat"]
	  This text is displayed for people who bought either a nice pair of shoes or a great fashionable coat.
	[/if]
	
### Nesting `if` statements

You can nest statements but you have to use iteration. This has to do with the limitations of the built-in shortcode API.

	[if qs="tonight:the-night"]
	  
	  Tonight's the night.
	  
	  [if2 qs="future:beautiful"]
	  
	    We create our own destiny every day we live.
	  
	  [/if2]
	  
	  [if2 qs="future:plastic;sheets"]
	  
	    I see sheets of plastic in your future.
	  
	  [/if2]
	  
	[/if]
	
You can nest up to if4 (4 levels).

	
### To do's
Planned for future releases:

* Add `[else]` and `[elseif]` statements
* Add regular expression matching
* Add 'not equals' operator (!=)
* Add ranges, match between numeric values, test x < y and x > y.
* Add more condition types / things to test.

[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/superinteractive/wordpress-conditional-content/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

