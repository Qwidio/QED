I'm for sure forgot to add some refs, that'll be future me problem.
this refs may not contain everything that may already contained in [this references of the other repo](https://github.com/Qwidio/CrossGate-Community-Collection/blob/main/references.md)

## router and path security related (preventing lfi mostly)
* route segment whitelist regex to kill directory traversal and null bytes <br>
[https://www.php.net/manual/en/function.preg-match.php](https://www.php.net/manual/en/function.preg-match.php)
[https://www.php.net/manual/en/function.preg-match.php](https://www.php.net/manual/en/function.preg-match.php)
* resolving canonical path and preventing directory traversal <br>
[https://www.php.net/manual/en/function.realpath.php](https://www.php.net/manual/en/function.realpath.php) <br>
[https://stackoverflow.com/questions/4205141/preventing-directory-traversal-in-php-but-allowing-paths](https://stackoverflow.com/questions/4205141/preventing-directory-traversal-in-php-but-allowing-paths)
* prefix check so nothing escapes the apps root folder <br>
[https://www.php.net/manual/en/function.str-starts-with.php](https://www.php.net/manual/en/function.str-starts-with.php)
* rewrite everything to index.php front controller but still serve static assets directly <br>
[https://stackoverflow.com/questions/25956054/htaccess-serve-static-files-route-everything-else-to-index-php](https://stackoverflow.com/questions/25956054/htaccess-serve-static-files-route-everything-else-to-index-php)

## output buffering and layout rendering stuff
* output buffering for capturing child view before wrapping layout <br>
[https://www.php.net/manual/en/function.ob-start.php](https://www.php.net/manual/en/function.ob-start.php) <br>
[https://www.php.net/manual/en/function.ob-get-clean.php](https://www.php.net/manual/en/function.ob-get-clean.php) <br>
[https://stackoverflow.com/questions/71401093/what-is-the-purpose-of-the-ob-functions-in-php-ob-start-ob-get-contents](https://stackoverflow.com/questions/71401093/what-is-the-purpose-of-the-ob-functions-in-php-ob-start-ob-get-contents)
* header lifecycle so pages can redirect or set titles before html renders <br>
[https://www.php.net/manual/en/function.header.php](https://www.php.net/manual/en/function.header.php) <br>
[https://stackoverflow.com/questions/8028957/how-to-fix-headers-already-sent-error-in-php](https://stackoverflow.com/questions/8028957/how-to-fix-headers-already-sent-error-in-php)

## isolating scopes and the components
* self-invoking anonymous static closures to keep variables out of global scope <br>
[https://www.php.net/manual/en/functions.anonymous.php](https://www.php.net/manual/en/functions.anonymous.php) <br>
[https://stackoverflow.com/questions/5860439/isolation-in-php](https://stackoverflow.com/questions/5860439/isolation-in-php)
* extracting component props without overwriting existing vars <br>
[https://www.php.net/manual/en/function.extract.php](https://www.php.net/manual/en/function.extract.php)

## database & config
* modern parameterized queries without the annoying bind_param boilerplate (php 8.2+) <br>
[https://www.php.net/manual/en/mysqli.execute-query.php](https://www.php.net/manual/en/mysqli.execute-query.php)
* strict exception mode for mysqli so errors actually throw <br>
[https://www.php.net/manual/en/mysqli-driver.report-mode.php](https://www.php.net/manual/en/mysqli-driver.report-mode.php)
* reading .env without needing composer or external packages <br>
[https://www.php.net/manual/en/function.parse-ini-file.php](https://www.php.net/manual/en/function.parse-ini-file.php)

## csrf and cryptograph related thing
* secure random token generation <br>
[https://www.php.net/manual/en/function.random-bytes.php](https://www.php.net/manual/en/function.random-bytes.php) <br>
[https://www.php.net/manual/en/function.bin2hex.php](https://www.php.net/manual/en/function.bin2hex.php)
* constant-time string comparison to prevent timing attacks on csrf tokens <br>
[https://www.php.net/manual/en/function.hash-equals.php](https://www.php.net/manual/en/function.hash-equals.php) <br>
[https://stackoverflow.com/questions/6287903/how-to-properly-add-cross-site-request-forgery-csrf-token-using-php](https://stackoverflow.com/questions/6287903/how-to-properly-add-cross-site-request-forgery-csrf-token-using-php)

## more harder session security
* hardening session cookies with samesite, httponly, and strict mode directly in session_start <br>
[https://www.php.net/manual/en/function.session-start.php](https://www.php.net/manual/en/function.session-start.php) <br>
[https://stackoverflow.com/questions/10165424/how-secure-are-php-sessions](https://stackoverflow.com/questions/10165424/how-secure-are-php-sessions)

## for applying strict types and securing xss escaping
* scalar type declarations and strict typing directive <br>
[https://www.php.net/manual/en/language.types.declarations.php](https://www.php.net/manual/en/language.types.declarations.php) <br>
[https://www.php.net/manual/en/control-structures.declare.php](https://www.php.net/manual/en/control-structures.declare.php)
* context-safe html escaping with ent_quotes <br>
[https://www.php.net/manual/en/function.htmlspecialchars.php](https://www.php.net/manual/en/function.htmlspecialchars.php) <br>
[https://stackoverflow.com/questions/19584189/when-used-correctly-is-htmlspecialchars-sufficient-for-protection-against-all-x](https://stackoverflow.com/questions/19584189/when-used-correctly-is-htmlspecialchars-sufficient-for-protection-against-all-x) <br>
[https://stackoverflow.com/questions/3623236/htmlspecialchars-vs-htmlentities-when-concerned-with-xss](https://stackoverflow.com/questions/3623236/htmlspecialchars-vs-htmlentities-when-concerned-with-xss)

## re-API endpoints & response 
* setting http status codes properly <br>
[https://www.php.net/manual/en/function.http-response-code.php](https://www.php.net/manual/en/function.http-response-code.php)
* json encoding with throw on error <br>
[https://www.php.net/manual/en/function.json-encode.php](https://www.php.net/manual/en/function.json-encode.php)
