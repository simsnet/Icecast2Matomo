# Icecast2Matomo


This is a script I wrote to send listener information from an [Icecast](https://www.icecast.org/) server into a [Matomo (Piwik)](https://matomo.org/) instance.

---
**How It Works**

Matomo offers a [Tracking HTTP API](https://developer.matomo.org/api-reference/tracking-api) for sites and services where tracking may be very limited.  This script bridges the gap between Icecast and Matomo by making use of Icecast's XML output, then sending an HTTP request to Matomo for each listener—complete with their IP address and user agent.

---
**Requirements**

- PHP 7 or newer (tested to work in PHP 7.4)
- php_curl extension
- Crontab (for automatically running the script)
- An Icecast server with admin access
- A Matomo server with write/admin access

---
**Getting Started**

1. Download the script to your Icecast server.
2. Customize the script to add your Icecast and Matomo server URLs, as well as change any links and provide your auth token.
3. Run the script using `php /path/to/script.php` and check Matomo for results.  The console will log all connections to Matomo by IP along with the HTTP code (should always be "200").  A successful run will show something close to this:

```
[mm-dd-yyyy, hh-mm-ss]
Sent request to Matomo for x.x.x.x (200)
Sent request to Matomo for x.x.x.x (200)
... continues for each IP ...
Complete!
```

If no listeners are connected:

```
[mm-dd-yyyy, hh-mm-ss]
No listeners connected.
Complete!
```

5. If successful, configure Crontab to automatically run the script.  I run it every minute on my server but feel free to make it as often as you'd like.  Be sure to configure Matomo's archival process!  See [crontab](https://github.com/simsnet/Icecast2Matomo/blob/main/crontab) for a complete list.

---
**Shoutout**

Big thanks to [iroks](https://github.com/iroks) for their [Icecast2GoogleAnalytics](https://github.com/iroks/Icecast2GoogleAnalytics) program.  This was my main solution for a long time until Google Analytics phased out Universal Analytics and began pushing GA4.  The library used for GA hasn't been updated for GA4.  I've borrowed its naming convention because these are both adapters from Icecast to an analytics service.
