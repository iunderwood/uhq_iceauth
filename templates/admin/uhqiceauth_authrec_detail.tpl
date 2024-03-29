<{if $data.error}>
<span style="color: red; "><{$data.error}></span>
<{elseif $data}>
<{$smarty.const._AM_UHQICEAUTH_ADR_DETAIL}><{$data.sequence}><br>
<br>
<hr>
<br>
<b><{$smarty.const._AM_UHQICEAUTH_ADR_LOGTIME}></b> <{$data.logtime}><br>
<{if $data.stoptime}>
<b><{$smarty.const._AM_UHQICEAUTH_ADR_STOPTIME}></b> <{$data.stoptime}> - <{$data.duration}> <{$smarty.const._AM_UHQICEAUTH_ADR_SECONDS}>
<br>
<{/if}>

<b><{$smarty.const._AM_UHQICEAUTH_ADR_RECTYPE}></b>
<{if $data.authtype == "L"}><{$smarty.const._AM_UHQICEAUTH_ADR_LREC}>
<{elseif $data.authtype == "S"}><{$smarty.const._AM_UHQICEAUTH_ADR_SREC}>
<{elseif $data.authtype == "A"}><{$smarty.const._AM_UHQICEAUTH_ADR_AREC}>
<{/if}>
<br>

<{if $data.authstat == "F"}>
<br><b><span style="color: red; "><Authentication Failed><{$smarty.const._AM_UHQICEAUTH_ADR_AUTHFAIL}> :: <{$data.authinfo}></span></b>
<br>
<{/if}>

<br>
<b><{$smarty.const._AM_UHQICEAUTH_ADR_SERVER}></b> <{$data.server}>:<{$data.port}><{$data.mount}><br>
<b><{$smarty.const._AM_UHQICEAUTH_ADR_CLIENT}></b> <{$data.clientid}><br>

<br>
<b><{$smarty.const._AM_UHQICEAUTH_ADR_USER}></b>
<{if $data.username}>
<{$data.username}>
<{else}>
<{$smarty.const._AM_UHQICEAUTH_ADR_ANON}>
<{/if}>
<br>

<b><{$smarty.const._AM_UHQICEAUTH_ADR_USERIP}></b> <{$data.userip}>
<{if $data.checkdns === true}>
::
<{if $data.userrdns != null}>
<{if $data.userrdns == $data.userip}>
<{$smarty.const._AM_UHQICEAUTH_ADR_NORES}>
<{else}>
<{$data.userrdns}>
<{if $data.userrdns == $data.currentrdns}>
<i>(<{$smarty.const._AM_UHQICEAUTH_ADR_NOCHG}>)</i>
<{else}>
<i>(<{$smarty.const._AM_UHQICEAUTH_ADR_NOW}> <{$data.currentrdns}>)</i>
<{/if}>
<{/if}>
<{else}>
<{$smarty.const._AM_UHQICEAUTH_ADR_NOREC}>
<{/if}>
<{/if}>
<br>

<b><{$smarty.const._AM_UHQICEAUTH_ADR_UA}></b>
<{if $data.useragent}>
<{$data.useragent}>
<{else}>
<{$smarty.const._AM_UHQICEAUTH_ADR_UANP}>
<{/if}>
<br>

<br>
<b><{$smarty.const._AM_UHQICEAUTH_ADR_GEOLOC}></b>
<{if $data.geocc}>
<{$data.geocc}>
<{if $data.georegion}> - <{$data.georegion}><{/if}>
<{if $data.geocity}> - <{$data.geocity}><{/if}>
<{else}>
<{$smarty.const._AM_UHQICEAUTH_ADR_NOGEO}>
<{/if}>
<br>
<br>
<b><{$smarty.const._AM_UHQICEAUTH_ADR_REFERER}></b>
<{if $data.referer}>
<{$data.referer}>
<{else}>
<span style="color: green; ">-</span>
<{/if}>
<br>
<{/if}>
<br>
<hr>
<br>
<a href='authrec.php'><{$smarty.const._AM_UHQICEAUTH_ADR_RETURN}></a>
