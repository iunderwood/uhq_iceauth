uhq_iceauth :: readme.txt

This document is up-to-date for UHQ-IceAuth v0.6

==[ Credits ]==

Module created and maintained by Ian A. Underwood (iunderwood).

Concept and some code from IceCast2 Auth (ice), by Mark McRitchie.
Framework-free admin header as implemented by Zoullou.
Admin EXM Icons from the Crystal Project (http://www.everaldo.com/crystal/).
Thanks to Karl Hayes of Xiph.org for his assistance.

==[ Description ]==

This module provides a mechanism by which Icecast can authenticate a listener request against your XOOPS database.

This version has been tested against XOOPS 2.3.3.

Icecast 2.3 is required. (2.3.2 is recommended).
The IceCast KH branch is required for source authentication and intro file dispersion.  2.3.2-KH18 is the most recently tested branch w/ this module.  Earlier KH branch versions are not recommended.

See changelog.txt for details, and readme.txt for configuration information.

==[ License ]==

All source code for this module is licensed under the Creative Commons GNU General Public license.  All code is copyright 2008-2009 by Ian Underwood.  For more information, visit the following URL:

http://creativecommons.org/licenses/GPL/2.0/

The full text of the GPL is included in license.txt.

==[ The Story ]==

While working on a demo site for an online radio station, I was doing some searches for XOOPS and Icecast and came across the "ice" module which was written for an earlier version of XOOPS sometime back in 2006 by Mark McRitchie.  I was quite giddy at the thought of be able to authenticate a test-stream for the radio station's staff.

To my dismay, I found that the module failed to work under XOOPS 2.3 RC, and that the module saw no further maintenance.  Even with some modifications to the module, I was unable to make it work as I really wanted it to.

As a result, I decided to make a quick rewrite of the module while recycling some of the code (and learning a couple of neat techniques in the process!).  The current version of the end product is almost completely different than the original base!  The database has been completely revamped and now can store servers, authentication, and accounting records.  Additionally, error conditions are reported back to the requesting Icecast server, making the error.log useful again.

==[ IceCast Setup ]==

The module needs to be setup on the website, and on any IceCast server you wish to configure authentication on.

--[ Mountpoint Configuration ]--

In order to use the listener authentication, a given icecast mountpoint must be configured to authenticate externally.  For example, the mountpoint test.ogg would use the following configuration:

<mount>
  <mount-name>/test.ogg</mount-name>
  <authentication type="url">
    <option name="listener_add" value="http://myserver/modules/uhq_iceauth/auth.php"/>
    <option name="listener_remove" value="http://myserver/modules/uhq_iceauth/auth.php"/>
  
    <option name="user" value="user"/>
    <option pass="pass" value="pass"/>
    <option name="auth_header" value="icecast-auth-user: 1"/>
    <option name="timelimit_header" value="icecast-auth-timelimit:"/>
  </authentication>
</mount>

If a website outage could be distressful for your operations, or if you have no need for authentication, you may configure your mountpoint to only report back data after a listener disconnects.  The idea here is to request an arbitrary webpage, and look for the HTTP OK in the headers.  In this case, the IceCast server requests its own status page as an authenticator.

<mount>
  <mount-name>/test.ogg</mount-name>
  <authentication type="url">
    <option name="listener_add" value="http://127.0.0.1:8000/status.xsl"/>
    <option name="listener_remove" value="http://myserver/modules/uhq_iceauth/auth.php"/>
    <option name="user" value="user"/>
    <option pass="pass" value="pass"/>
    <option name="auth_header" value="HTTP/1.0"/>
  </authentication>
</mount>

Starting with IceCast 2.3.2-kh2, this can be reduced to the following:

<mount>
  <mount-name>/test.ogg</mount-name>
  <authentication type="url">
    <option name="listener_remove" value="http://myserver/modules/uhq_iceauth/auth.php"/>
  </authentication>
</mount>

Additionally, if you want to use authentication with relayed mountpoints (e.g. from some other master server), you need to define both the mount and the relay as required.  (Maybe this is obvious to you, but I was oblivious!)

Do note that with the current version of IceCast, using the above accounting-only option does not have any IP or User Agent information.

--[ Mountpoint Logging ]--

The module is also capable of recording mountpoint logging information from IceCast.  The logs are simple records which only include the server, port, and mountpoint and if the mountpoint is being added or removed.  These are informational messages only, and can be useful when gauging the usage of on-demand relays.

They are configured per-mountpoint as follows.  You may omit the <authentication> tag if you are already using it:

  <authentication type="url">
    <option name="mount_add" value="http://myserver/modules/uhq_iceauth/auth.php"/>
    <option name="mount_remove" value="http://myserver/modules/uhq_iceauth/auth.php"/>
  </authentication>

Additionally, the module stores a list of known active mounts in the database.  Do note that the list may not be accurate if a mount goes out of service and the module does not receive the message.

--[ Source Authentication ]--

In order to use source authentication, you need to be running an IceCast server in the KH development branch which can be found here:

http://www.icecast.pwp.blueyonder.co.uk/

Additionally, you need to configure the following option under the mount.  You do not need to specify the <authentication/> tag if you have already done so:

  <authentication type="url">
    <option name="stream_auth" value="http://myserver/modules/uhq_iceauth/auth.php"/>
  </authentication>

==[ Module Setup ]==

--[ General Module Configuration ]--

The module supports a few global options which apply to all Icecast authentication options:

* Auth Header
  This is the header that is returned when authentication is successful and the user is allowed to stream.

* Message Header
  This is the header that is returned when authentication fails.  The cause of the error follows this header.

* Time-Limit Header
  This is the header that carries the amount of time allowed on the stream at any one time.  The value that follows this header is an integer which defines the amount of allowed time in seconds.

The following options dictate how to react to an authentication request for a mountpoint that isn't explicitly configured in this module:

* Undefined Handling
  In the event an authentication request is made for a mountpoint/server that is not in the database, action will be determined by the value of this section.

  Always Allow - This will always send a message back passing authentication.
  Never Allow - This will always send a message back failing autentication.
  Check UN/PW - This will process the username and password, and check against the default groups below.

* Default Groups
  These groups will be allowed to pass authentication on undefined mountpoints.

* Default Time Limit
  This is the time limit that will be allowed if a user passes the credential and group check for undefined mount points.  Set this to 0 to allow for an unlimited stream time.
  
--[ Managing Mountpoints ]--

There are no sample servers inserted into the database when the module is first installed.  Adding servers is not a mandatory exercise for the module, but if you are interested in setting permissions based on a given mountpoint, you will need to explicitly configure each mountpoint.

The fields are:

* Server IP/FQDN:
  This is the IP address or domain name of the server in place.
  
* Port:
  This is the port that the server is running on.
  
* Mountpoint:
  This is the mountpoint that is being managed.  All IceCast mountpoints start with a trailing slash, e.g. /testing.ogg.
  
* Time Limit (sec):
  This is the number of seconds that a listener will be allowed to remain connected to a given stream.

* Listener Auth:
  Listener authentication can be either Anonymous, or checking a user's credentials against the XOOPS database.

* Listener Group:
  Listeners need to be a member of the groups specified in this list in order to be allowed to listen to the stream.

The last four options are required for source authentication only:

* Source Auth:
  Source authentication can either be statically defined, or based upon a user's credentials.

* Source Group:
  Similar to the listener group, any source client must be a member of these groups in order to be allowed to broadcast.

* Source UN:
  This is the static username for a source if that option is in use.  Most clients by default use a name of "source".

* Source PW:
  This is the password required along with the source username.

Editing mountpoints uses the same form.

--[ Managing Introductions ]--

You may use the "Intros" tab to upload and configure introductions for use on your streams, so long as you are using an Icecast version that supports "withintro", introduced in Icecast 2.3.2-KH16.

Please be aware that each intro file needs to match the same characteristics of your mountpoints.  A stream which runs at 64k mp3 should have an introduction file with the same characteristics.  This can result in quite a collection of introductions, but there is no on-the-fly transcoding upon connections.

Additionally, the introductions will be sent out with each successful authentication request.  Bear this in mind if you have a popular station as this may impact your site's bandwidth usage.

In order to upload introductions, you will need to modify the default MIME types which XOOPS understands.  This file is /class/mimetypes.inc.php, in XOOPS version < 2.4, and /include/mimetypes.inc.php in latest releases.

--[ Availablity Notice ]--

Be aware, that if your XOOPS site is unavailable for any reason, you may not be able to authenticate either sources or listeners that depend on it.

--[ Logging ]--

Logging functions in the module are limited to explicitly defined server/ip/mountpoint combinations only.  I did this to prevent logging data from unexpected hosts from taking up SQL space, which could introduce a denial-of-service condition.

Please be sure to have adequate SQL database space in the event your station receives a lot of hits.  I have not devised a method to boil down statistics yet, but may do so if there is enough interest and I can get a good enough data set to work with.

--[ Statistics ]--

A very rudimentary set of statistics on the IceAuth data has been included since v0.3.  At some point, the statistic info developed for use with the module will be moved to another module entirely as to not clutter-up the codebase.

Development on statistic forms and information will be limited in IceAuth because that is beyond the original scope of the module, which is solely to handle authentication and accounting for IceCast streams.

To that end, once IceAuth reaches a state of somewhat feature completeness, more complex and meaningful statistics will be made available in a module to be developed called RadioStats.  This will remove statistics from the administrative interface such that any appropriate staff personnel can look at how things are running without needing full access to the nuts and bolts of your site.

This module will also allow certain servers to be grouped together or excluded ... such that statistics on testing streams do not cloud or interfere with a main set of statistics.  This could also be good for keeping stats on on-demand relays out of main stats as well.

However, if you have external access to your SQL database, you can run whatever queries you please.

==[ Bug Reports ]==

You may drop me bug reports or feature requests: xoops@underwood-hq.org.

If there is a specific bug you are experiencing, the more information I have the better.

++I;