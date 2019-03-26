# xatClass
> All in one ready to use in your application xat-based.

# Why should I use it?
* It allows you to access a lot of hidden features in xat;
* It may be used by developers to help their projects;
* It is only connected to official xat files, not external;
* It is fully safe;


# Functions & Modes
+ shortname 
  * Get shortname prices
  * **Required:** (alpha-numeric)
  * **Return:** (string) or (int)
  
- chatgroup
  * Get chat prices
  * **Required:** (alpha-numeric)
  * **Return:** (string) or (int)
  
+ dx
  * Convert days to xats
  * **Required:** (numeric)
  * **Return:** (int)
  
- xd
  * Convert xats to days
  * **Required:** (numeric)
  * **Return:** (int)
  
+ promotion
  * Get chat promotion prices
  * **Required:** (float 0.5 * 6)
  * **Return:** (string) or (object)
  
- bannercheck
  * Verify if the banner URL is approved
  * **Required:** (alpha-numeric)
  * **Return:** (string) or (int)
  
+ powerinfo
  * Get power information
  * **Required:** (alpha-numeric)
  * **Return:** (object)
  
- id2reg
  * Convert ID to register
  * **Required:** (numeric)
  * **Return:** (string)
  
+ reg2id
  * Convert register to ID
  * **Required:** (alpha-numeric)
  * **Return:** (int)
  
- delistcheck
  * Verify if the chat is delisted
  * **Required:** (alpha-numeric)
  * **Return:** (int)
  
+ chatsearch
  * Search for xat chats
  * **Required:** (alpha-numeric)
  * **Return:** (object)
  
- chatinfo
  * Get information about chats
  * **Required:** (alpha-numeric)
  * **Return:** (object)
  
+ gifts
  * Get gifts from an xat user
  * **Required:** (numeric)
  * **Return:** (object)
  
- latest
  * Get information about the latest power
  * **Return:** (object)
  
+ store
  * Get all xat store prices
  * **Return:** (object)
  
- promoted
  * Get chats promoted
  * **Return:**(object)

+ pcd
  * Get countdown from the latest power
  * **Return:** (object)
  
- hugs
  * Load the hug list
  * **Return:** (object)
  
+ jinx
  * Load the jinx list
  * **Return:** (object)
  
- countries
  * See top five countries where xat is most common
  * **Return:** (object)
  
# Examples:
> Actually you can test it using <https://xatblog.net/api/load.php?t=MODE&v=ARGS>

* <https://xatblog.net/api/load.php?t=shortname&v=DaBest>
* <https://xatblog.net/api/load.php?t=id2reg&v=42>
* <https://xatblog.net/api/load.php?t=latest>
* <https://xatblog.net/api/load.php?t=promoted>

# License & Version:
* MIT
* Version: 1.0
