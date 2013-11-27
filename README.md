WordPress Conditional Content
=================

A simple but powerful WordPress plugin that allows you to use shortcodes as if statements in your content to display text only if certain conditions are met.

## How to use
You can place conditional content between `if` [shortcodes](http://codex.wordpress.org/Shortcode), and use the attributes of the shortcode to define conditions. 

**Basic example:**

	[if qs="product-type:shoes"]
		Thank you for buying shoes
	[/if]
	
The text in this example will only be displayed if the current URL has a GET paramater of "product-type" with the value "shoes".

### Defining conditions

Conditions are defined by setting the attributes of the shortcode in the following format:

	[if <type>=<condition>]
		Hello world
	[/if]
	
Available condition types are:

* **`referrer`** - To check on the current referrer. The condition will be true if `HTTP_REFERER` contains `<value>`. The match doesn't need to be exact, so if a user arrives from google and `<value>` is set to "google.com" the condition will be true.

* **`role`** - Matches the current user's role. The match needs to be exact so "admin" will not match "administrator".

* **`qs`** - Match on the key/value pair of the defined query string/GET parameter. `qs="<key>:<value>"`. For example, the condition `qs="product-type:2"` will match `?product-type=2`.

 
### Some examples:

Display content based on query string:

	[if qs="myvar:2"]
		This content is only displayed if the current URL contains a GET paramater 'myvar' with value 2
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

	[if referrer="www.example.com" qs="utm_source:rss"]
		This content is displayed to users coming from example.com who clicked on a link originating from our RSS feed.
	[/if]
	
### Matching with `exact` or `contain`

By default query string conditions are matched on the exact value as defined in the shortcode. For looser 'wildcard' matching you can add a `match` attribute with the value `contain`:

	[if qs="product-type:cashmere-" match="contain"]
		Cashmere is a wonderful fabric
	[/if]

This will match both `?product-type=cashmere-sweater` and `?product-type=cashmere-coat`
	
### Matching on multiple values
You can have a condition match on multiple values by using the semicolon as a seperator when defining allowed values. Example:

	[if qs="product-type:shoes;coat"]
	  This text is displayed for people who bought either a nice pair of shoes or a great fashionable coat.
	[/if]
	
### To do's
Planned for future releases:

* `[else]` and `[elseif]` statements
* Regular expression matching