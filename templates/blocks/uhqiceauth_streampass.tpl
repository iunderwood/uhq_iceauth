<{if $block.error}>
<{$block.error}>
<{else}>
<{$smarty.const._MB_UHQICEAUTH_UN}> <b><{$block.username}></b><br>
<{$smarty.const._MB_UHQICEAUTH_PW}> <b><{$block.password}></b><br>
<hr>
<div style="text-align: center;">
    <form method=GET action="/modules/uhqiceauth/clearstreampass.php">
        <input type="submit" value="<{$smarty.const._MB_UHQICEAUTH_RESET}>">
    </form>
</div>
<{/if}>
