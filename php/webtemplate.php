<?php

function template($a,$b,$c,$d,$e) {
$r="";

if ($a==="gab.com" & strpos($b,"posts")!==FALSE) {
//start
$ax=$c;
$ax=substr($ax,strpos($ax,",\"account\":\"")+13);
$ax=substr($ax,strpos($ax,"\",\"acct\":\"")+10);
$an=substr($ax,0,strpos($ax,",")-1);
$ax=substr($ax,strpos($ax,"\",\"display_name\":\"")+18);
$dn=substr($ax,0,strpos($ax,",")-1);
$ax=substr($ax,strpos($ax,",\"card\":")+8);
$card=substr($ax,0,strpos($ax,"}"));
$card=substr($card,strpos($card,"{\"url\":")+6);
$url=substr($card,0,strpos($card,","));
$card=substr($card,strpos($card,",\"title\":")+11);
$title=substr($card,0,strpos($card,",")-1);
$card=substr($card,strpos($card,",\"description\":")+16);
$desc=substr($card,0,strpos($card,",")-1);

$r.="<html><base href=https://gab.com><head><meta charset=\"utf-8\"><meta content=\"width=device-width, initial-scale=1\" name=\"viewport\" viewport-fit=\"cover\"><link href=\"/favicon.ico\" rel=\"icon\" type=\"image/x-icon\"><link href=\"/apple-touch-icon.png\" rel=\"apple-touch-icon\" sizes=\"180x180\"><link color=\"#2B90D9\" href=\"/mask-icon.svg\" rel=\"mask-icon\"><link href=\"/manifest.json\" rel=\"manifest\"><meta content=\"/browserconfig.xml\" name=\"msapplication-config\"><meta content=\"#282c37\" name=\"theme-color\"><meta content=\"yes\" name=\"apple-mobile-web-app-capable\"><title>".$an.": \"".$desc."\" | gab.com - Gab Social</title><link rel=\"stylesheet\" media=\"all\" href=\"/packs/css/common-05893041.css\"><link rel=\"stylesheet\" media=\"all\" href=\"/packs/css/default-2ae0f3ee.chunk.css\"><script src=\"/packs/js/common-e73150f4876844f8c1d1.js\" crossorigin=\"anonymous\"></script><script src=\"/packs/js/locale_en-4880512ce1277988e28c.chunk.js\" crossorigin=\"anonymous\"></script><link rel=\"preload\" href=\"/packs/js/features/getting_started-e5d7920a8ba5d9a852be.chunk.js\" as=\"script\" type=\"text/javascript\" crossorigin=\"anonymous\"><link rel=\"preload\" href=\"/packs/js/features/compose-967522b61d9c20fad72a.chunk.js\" as=\"script\" type=\"text/javascript\" crossorigin=\"anonymous\"><link rel=\"preload\" href=\"/packs/js/features/home_timeline-922306e55154034b166d.chunk.js\" as=\"script\" type=\"text/javascript\" crossorigin=\"anonymous\"><link rel=\"preload\" href=\"/packs/js/features/notifications-686a1ea14667ef2fe673.chunk.js\" as=\"script\" type=\"text/javascript\" crossorigin=\"anonymous\"><script src=\"/packs/js/application-215bbb59388c6710b5e2.chunk.js\" crossorigin=\"anonymous\"></script><link href=\"";

//do once
$aa=str_replace("/posts/","/updates/",$a);
$aa=substr($aa,0,strrpos($aa,"/"));

//replace these numbers
$r.="https://gab.com/users/";
$r.=$aa;
$r.="/101608050.atom\" rel=\"alternate\" type=\"application/atom+xml\">";
$r.="<link href=\"https://gab.com/api/oembed.json?url=https%3A%2F%2Fgab.com/users".$aa."%2F101608050\" rel=\"alternate\" type=\"application/json+oembed\">";
$aa=str_replace("/posts/","/statuses/",$a);
$aa=substr($aa,0,strrpos($aa,"/"));

//ok
$r.="<link href=\"https://gab.com/users/".$aa."\" rel=\"alternate\" type=\"application/activity+json\">";
$r.="<meta content=\"Gab Social\" property=\"og:site_name\"><meta content=\"article\" property=\"og:type\">";

//need to identify real name
$aa=substr($a,1);
$aa=substr($aa,0,strpos($aa,"/"));
$ax=$c;
$ax=substr($ax,strpos($ax,",\"account\":\"")+13);
$ax=substr($ax,strpos($ax,"\",\"acct\":\"")+10);
$an=substr($ax,0,strpos($ax,",")-1);
$ax=substr($ax,strpos($ax,"\",\"display_name\":\"")+18);
$dn=substr($ax,0,strpos($ax,",")-1);


$r.="<meta content=\"".$dn." (@".$an."@gab.com)\" property=\"og:title\">";
$r.="<meta content=\"".$a."\" property=\"og:url\">";

//this uses c or other input
$ax=$c;
$ax=substr($ax,strpos($ax,",\"content\":\"")+12);
$content=substr($ax,0,strpos($ax,"\",\"reblog"));
$content=str_replace("\\u003c","<",$content);
$content=str_replace("\\u003e",">",$content);
$content=str_replace("\u0026apos;","'",$content);
$content=str_replace("\u0026quot;","\"",$content);
$content=strip_tags($content);
$r.="<meta content=\"".$content."\" name=\"description\">";
$r.="<meta content=\"".$content."\" property=\"og:description\">";
//this uses c or other source
$ax=$c;
$ax=substr($ax,strpos($ax,"\",\"avatar\":\"")+12);
$pic=substr($ax,0,strpos($ax,",")-1);
$r.="<meta content=\"".$pic."\" property=\"og:image\">";


//not sure if done
$r.="<meta content=\"120\" property=\"og:image:width\"><meta content=\"120\" property=\"og:image:height\"><meta content=\"summary\" property=\"twitter:card\"></head><body class=\"app-body theme-default no-reduce-motion\"><div class=\"app-holder\" data-props=\"{&quot;locale&quot;:&quot;en&quot;}\" id=\"gabsocial\">";



//do once
$r.="<div tabindex=\"-1\">";
$r.="<div class=\"ui\">";
$r.="<nav class=\"tabs-bar logged-in\"><div class=\"tabs-bar__container\"><div class=\"tabs-bar__split tabs-bar__split--left\"><a class=\"tabs-bar__link--logo\" data-preview-title-id=\"column.home\" aria-label=\"Home\" href=\"/home\" style=\"padding: 0px;\"><span>Home</span></a>";
$r.="<a class=\"tabs-bar__link\" data-preview-title-id=\"column.home\" aria-label=\"Home\" href=\"/home\"><i class=\"tabs-bar__link__icon home\"></i><span>Home</span></a><a class=\"tabs-bar__link\" data-preview-title-id=\"column.notifications\" aria-label=\"Notifications\" href=\"/notifications\"><i class=\"tabs-bar__link__icon notifications\"></i><span>Notifications</span></a>";
$r.="<a class=\"tabs-bar__link\" data-preview-title-id=\"column.groups\" aria-label=\"Groups\" href=\"/groups\"><i class=\"tabs-bar__link__icon groups\"></i><span>Groups</span></a><a class=\"tabs-bar__link tabs-bar__link--trends\" href=\"https://trends.gab.com\" data-preview-title-id=\"tabs_bar.trends\" aria-label=\"tabs_bar.trends\"><i class=\"tabs-bar__link__icon trends\"></i><span>Trends</span></a></div><div class=\"tabs-bar__split tabs-bar__split--right\">";
$r.="<div class=\"tabs-bar__search-container\"><div class=\"search\"><label><span style=\"display: none;\">Search</span><input class=\"search__input\" type=\"text\" placeholder=\"Search\" value=\"\"></label><div role=\"button\" tabindex=\"0\" class=\"search__icon\"><i role=\"img\" alt=\"search\" class=\"fa fa-search active\">";
$r.="</i><i role=\"img\" alt=\"times-circle\" class=\"fa fa-times-circle\" aria-label=\"Search\"></i></div></div></div><div class=\"flex\"><div class=\"tabs-bar__profile\"><div class=\"account__avatar\">";
$r.="</div><button class=\"tabs-bar__sidebar-btn\"></button><div class=\"compose__action-bar\" style=\"margin-top: -6px;\"><div class=\"compose__action-bar-dropdown\"><div><button aria-label=\"Menu\" title=\"Menu\" class=\"icon-button\" tabindex=\"0\" style=\"font-size: 34px; width: 43.7143px; height: 43.7143px; line-height: 34px;\">";
$r.="<i role=\"img\" alt=\"chevron-down\" class=\"fa fa-chevron-down fa-fw\" aria-hidden=\"true\"></i></button></div></div></div></div><span class=\"tabs-bar__page-name\">Profile</span><button class=\"tabs-bar__button-compose button\" aria-label=\"Gab\"><span>Gab</span></button><a class=\"tabs-bar__search-btn\" href=\"/search\">";
$r.="<i class=\"tabs-bar__link__icon tabs-bar__link__icon--search\"></i><span>Search</span></a></div></div></div></nav><div class=\"page\"><div class=\"page__columns\"><div class=\"columns-area__panels\"><div class=\"columns-area__panels__pane columns-area__panels__pane--left\"><div class=\"columns-area__panels__pane__inner\"></div></div>";
$r.="<div class=\"columns-area__panels__main\"><div class=\"columns-area columns-area--mobile\"><div role=\"region\" class=\"column\"><button class=\"column-back-button\"><i role=\"img\" alt=\"chevron-left\" class=\"fa fa-chevron-left column-back-button__icon fa-fw\"></i><span>Back</span></button><div class=\"column-header__wrapper\">";
$r.="<h1 class=\"column-header\"><div class=\"column-header__buttons\"><button class=\"column-header__button\" title=\"Show less for all\" aria-label=\"Show less for all\" aria-pressed=\"true\"><i role=\"img\" alt=\"eye\" class=\"fa fa-eye\"></i></button></div></h1><div class=\"column-header__collapsible collapsed\" tabindex=\"-1\">";
$r.="<div class=\"column-header__collapsible-inner\"></div></div></div><div>";


//user comments
$ex=explode("\",\"created_at\":\"",$d);
$cc=count($ex);
for($i=0;$i<$cc;$i++){
if ($i!=0) {$ax=$ex[$i];} else {$ax=substr($c,strpos($c,"created_at")+13);}
$time=substr($ax,0,strpos($ax,"."))."+0000";
new DateTimeZone('UTC');
$time=Date("U",strtoTime($time));
$ax=substr($ax,strpos($ax,",\"in_reply_to_id\":\"")+18);
$r2=substr($ax,1,strpos($ax,",")-2);
$ax=substr($ax,strpos($ax,"\",\"uri\":")+9);
$uri=substr($ax,0,strpos($ax,",")-1);
$ax=substr($ax,strpos($ax,"\",\"replies_count\":")+18);
$rep=substr($ax,0,strpos($ax,","));
$ax=substr($ax,strpos($ax,",\"reblogs_count\":")+17);
$rbc=substr($ax,0,strpos($ax,","));
$ax=substr($ax,strpos($ax,",\"favourites_count\":")+20);
$fv=substr($ax,0,strpos($ax,","));
$ax=substr($ax,strpos($ax,",\"quote_of_id\":")+15);
$quoted=substr($ax,0,strpos($ax,",\"favourited\":"));
$ax=substr($ax,strpos($ax,",\"content\":\"")+12);
$content=substr($ax,0,strpos($ax,"\",\"reblog")-1);
$content=str_replace("\\u003c","<",$content);
$content=str_replace("\\u003e",">",$content);
$content=str_replace("\u0026apos;","'",$content);
$content=str_replace("\u0026quot;","\"",$content);
$ax=substr($ax,strpos($ax,",\"account\":\"")+13);
$ax=substr($ax,strpos($ax,":{\"id\":\"")+8);
$id=substr($ax,0,strpos($ax,",")-1);
$ax=substr($ax,strpos($ax,"\",\"username\":\"")+14);
$usn=substr($ax,0,strpos($ax,",")-1);
$ax=substr($ax,strpos($ax,"\",\"acct\":\"")+10);
$account=substr($ax,0,strpos($ax,",")-1);
$ax=substr($ax,strpos($ax,"\",\"display_name\":\"")+18);
$dn=substr($ax,0,strpos($ax,",")-1);
$ax=substr($ax,strpos($ax,"\",\"avatar\":\"")+12);
$pic=substr($ax,0,strpos($ax,",")-1);
$ax=substr($ax,strpos($ax,",\"is_pro\":")+10);
$ipro=substr($ax,0,strpos($ax,","));
$ax=substr($ax,strpos($ax,",\"is_verified\":")+15);
$iv=substr($ax,0,strpos($ax,","));
$ax=substr($ax,strpos($ax,",\"is_donor\":")+12);
$isd=substr($ax,0,strpos($ax,","));
$ax=substr($ax,strpos($ax,",\"card\":")+8);
$card=substr($ax,0,strpos($ax,"}"));
$card=substr($card,strpos($card,"{\"url\":")+6);
$url=substr($card,0,strpos($card,","));
$card=substr($card,strpos($card,",\"title\":")+11);
$title=substr($card,0,strpos($card,",")-1);
$card=substr($card,strpos($card,",\"description\":")+16);
$desc=substr($card,0,strpos($card,",")-1);
$card=substr($card,strpos($card,",\"image\":")+10);
$image=substr($card,0,strpos($card,",")-1);
if ($i!=0) {$ax=substr($ax,strpos($ax,"{\"id\":")+6);} else {$ax=substr($c,7);}
$id2=substr($ax,0,strpos($ax,",")-1);


//$image=str_replace("/","\\",$image);

$r.="<div tabindex=\"-1\">";
if ($i==0) {
$r.="<div class=\"focusable detailed-status__wrapper\" tabindex=\"0\" aria-label=\"".strip_tags($content).",".date("F j, y, g:i a" ,$time).",".$account."\">";
$r.="<div style=\"box-sizing: border-box;\">";
$r.="<div class=\"detailed-status\">";
$r.="<a class=\"detailed-status__display-name active\" aria-current=\"page\" href=\"/".$account."\">";
$r.="<div class=\"detailed-status__display-avatar\"><div class=\"account__avatar\" style=\"width: 48px; height: 48px; background-image: url(&quot;".$pic."&quot;);\">";
$r.="</div>";
$r.="</div>";
$r.="<span class=\"display-name\"><bdi><strong class=\"display-name__html\">".$dn."</strong>";
$r.="</bdi>";
if ($iv==="true") {
$r.="<span class=\"verified-icon\"><span class=\"visuallyhidden\">Verified Account</span></span>";}
$r.="<span class=\"display-name__account\">@".$account."</span></span></a>";
$r.="<div tabindex=\"0\" class=\"status__content\" lang=\"en\" style=\"direction: ltr;\"><p>".$content."</p><p>";
$r.="<a href=\"".$url."\" rel=\"nofollow noopener\" target=\"_blank\" class=\"status-link\" title=\"".$url."\">";
$r.="<a href=\"".$url."\" class=\"status-card\" target=\"_blank\" rel=\"noopener\">";
$r.="<div class=\"status-card__image\">";
$r.="<div class=\"status-card__image-image\" style=\"background-image: url(&quot;".$image."&quot;);\">";
$r.="</div></div>";
$r.="<div class=\"status-card__content\"><strong class=\"status-card__title\" title=\"".$title."\">".$title."</strong>";
$r.="<p class=\"status-card__description\">".$desc."</p><span class=\"status-card__host\"><i role=\"img\" alt=\"link\" class=\"fa fa-link fa-fw\">";
$r.="</i> www.infowars.com</span></div></a><div class=\"detailed-status__meta\">";
$r.="<a class=\"detailed-status__datetime\" href=\"".$b."\" target=\"_blank\" rel=\"noopener\"><span>".$time."</span></a>";
$r.="<span> · <a class=\"detailed-status__application\" target=\"_blank\" rel=\"noopener\">Web</a></span>";
$r.=" · <a class=\"detailed-status__link\" href=\"".$b."/reblogs\">";
$r.="<i role=\"img\" alt=\"retweet\" class=\"fa fa-retweet\"></i>";
$r.="<span class=\"detailed-status__reblogs\">";
$r.="<span>".$rbc."</span></span></a> · <span class=\"detailed-status__link\">";
$r.="<i role=\"img\" alt=\"star\" class=\"fa fa-star\"></i><span class=\"detailed-status__favorites\">";
$r.="<span>".$fv."</span></span></span></div></div></div><div class=\"detailed-status__action-bar\"><div class=\"detailed-status__button\">";
$r.="<button aria-label=\"Reply\" title=\"Reply\" class=\"icon-button\" tabindex=\"0\" style=\"font-size: 18px; width: 23.1429px; height: 23.1429px; line-height: 18px;\">";
$r.="<i role=\"img\" alt=\"reply\" class=\"fa fa-reply fa-fw\" aria-hidden=\"true\">";
$r.="</i></button></div>";
$r.="<div class=\"detailed-status__button\">";
$r.="<button aria-label=\"Repost\" title=\"Repost\" class=\"icon-button\" tabindex=\"0\" style=\"font-size: 18px; width: 23.1429px; height: 23.1429px; line-height: 18px;\">";
$r.="<i role=\"img\" alt=\"retweet\" class=\"fa fa-retweet fa-fw\" aria-hidden=\"true\"></i></button></div>";
$r.="<div class=\"detailed-status__button\">";
$r.="<button aria-label=\"Quote\" title=\"Quote\" class=\"icon-button\" tabindex=\"0\" style=\"font-size: 18px; width: 23.1429px; height: 23.1429px; line-height: 18px;\">";
$r.="<i role=\"img\" alt=\"quote-left\" class=\"fa fa-quote-left fa-fw\" aria-hidden=\"true\"></i></button></div>";
$r.="<div class=\"detailed-status__button\">";
$r.="<button aria-label=\"Favorite\" title=\"Favorite\" class=\"star-icon icon-button\" tabindex=\"0\" style=\"font-size: 18px; width: 23.1429px; height: 23.1429px; line-height: 18px;\">";
$r.="<i role=\"img\" alt=\"star\" class=\"fa fa-star fa-fw\" aria-hidden=\"true\" style=\"transform: rotate(0deg);\"></i></button></div>";
$r.="<div class=\"detailed-status__action-bar-dropdown\"><div>";
$r.="<button aria-label=\"More\" title=\"More\" class=\"icon-button\" tabindex=\"0\" style=\"font-size: 18px; width: 23.1429px; height: 23.1429px; line-height: 18px;\">";
$r.="<i role=\"img\" alt=\"ellipsis-h\" class=\"fa fa-ellipsis-h fa-fw\" aria-hidden=\"true\"></i></button></div></div></div></div></div>"; 
}
else {
$diff=time()-$time;
$dt="";
if ($diff>60) {$dt=(int)($diff/60);$dt.="m";}
if ($diff>3600) {$dt=(int)($diff/3600);$dt.="h";}
if ($diff>3600*24) {$dt=(int)($diff/(60*60*24));$dt.="d";}
if ($diff>3600*24*7) {$dt=(int)($diff/(60*60*24*7));$dt.="w";}
if ($diff>3600*24*30) {$dt=(int)($diff/(60*60*24*30));$dt.="M";}
if ($diff>3600*24*365) {$dt=(int)$diff/((60*60*24*365));$dt.="Y";}

$r.="<div class=\"status__wrapper status__wrapper-public status__wrapper-reply focusable\" tabindex=\"0\" aria-label=\"".$dn.",".strip_tags($content)." ".$dt.",".$account."\">";
$r.="<div class=\"status status-public status-reply\" data-id=\"".$r2."\"><div class=\"status__expand\" role=\"presentation\"></div>";
$r.="<div class=\"status__info\"><a class=\"status__relative-time\" href=\"/".$account."/posts/".$id2."\">";
//figure time diff out later 
$r.="<time datetime=\"".$dt."\" title=\"".$dt."\">".$dt."</time></a><a class=\"status__display-name\" title=\"".$account."\" href=\"".$account."\">";
$r.="<div class=\"status__avatar\"><div class=\"account__avatar\" style=\"width: 48px; height: 48px; background-image: url(&quot;".$pic."&quot;);\"></div></div>";
$r.="<span class=\"display-name\"><bdi>";
if ($iv==="true") {
$r.="<span class=\"verified-icon\"><span class=\"visuallyhidden\">Verified Account</span></span>";}

$r.="<strong class=\"display-name__html\">".$dn."</strong></bdi><span class=\"display-name__account\">@".$account."</span></span></a></div>";
//replace alex jones with $a
$r.="<div tabindex=\"0\" class=\"status__content status__content--with-action\" lang=\"en\" style=\"direction: ltr;\"><p><span class=\"h-card\">";
$r.="</span><a href=\"https://gab.com/".$an."\" class=\"u-url mention status-link\" title=\"".$an."\">";
$r.="</a></span>".$content."\"</div><div class=\"status__action-bar\">";
$r.="<div class=\"status__action-bar__counter\"><button aria-label=\"Reply to thread\" title=\"Reply to thread\" class=\"status__action-bar-button icon-button\" tabindex=\"".$rep."\" style=\"font-size: 18px; width: 23.1429px; height: 23.1429px; line-height: 18px;\">";
$r.="<i role=\"img\" alt=\"reply-all\" class=\"fa fa-reply-all fa-fw\" aria-hidden=\"true\"></i></button></div><div class=\"status__action-bar__counter\">";
$r.="<button aria-label=\"Repost\" aria-pressed=\"false\" title=\"Repost\" class=\"status__action-bar-button icon-button\" tabindex=\"".$rbc."\" style=\"font-size: 18px; width: 23.1429px; height: 23.1429px; line-height: 18px;\"><i role=\"img\" alt=\"retweet\" class=\"fa fa-retweet fa-fw\" aria-hidden=\"true\"></i>";
$r.="</button></div><div class=\"status__action-bar__counter\"><button aria-label=\"Quote\" title=\"Quote\" class=\"status__action-bar-button icon-button\" tabindex=\"0\" style=\"font-size: 18px; width: 23.1429px; height: 23.1429px; line-height: 18px;\"><i role=\"img\" alt=\"quote-left\" class=\"fa fa-quote-left fa-fw\" aria-hidden=\"true\"></i>";
$r.="</button></div><div class=\"status__action-bar__counter\">";
$r.="<button aria-label=\"Favorite\" aria-pressed=\"false\" title=\"Favorite\" class=\"status__action-bar-button star-icon icon-button\" tabindex=\"".$fv."\" style=\"font-size: 18px; width: 23.1429px; height: 23.1429px; line-height: 18px;\"><i role=\"img\" alt=\"star\" class=\"fa fa-star fa-fw\" aria-hidden=\"true\" style=\"transform: rotate(0deg);\"></i></button></div><div class=\"status__action-bar-dropdown\"><div>";
$r.="<button aria-label=\"More\" title=\"More\" class=\"icon-button\" tabindex=\"0\" style=\"font-size: 18px; width: 23.1429px; height: 23.1429px; line-height: 18px;\"><i role=\"img\" alt=\"ellipsis-h\" class=\"fa fa-ellipsis-h fa-fw\" aria-hidden=\"true\"></i></button></div></div></div></div></div></div></div>";
//<div class=\"columns-area__panels__pane columns-area__panels__pane--right\">";
}}//end else for

//do once
$r.="</div></div></div></div></div><div class=\"columns-area__panels__pane__inner\"><div class=\"wtf-panel group-sidebar-panel\"><div class=\"wtf-panel__content\"><div class=\"group-sidebar-panel__items\"></div></div></div><div class=\"wtf-panel\"><div class=\"wtf-panel-header\"><i role=\"img\" alt=\"users\" class=\"fa fa-users wtf-panel-header__icon\"></i>";
$r.="<span class=\"wtf-panel-header__label\"><span>Who To Follow</span></span></div><div class=\"wtf-panel__content\"><div class=\"wtf-panel__list\">";
//in case we add suggested users later.
//<div class=\"account\"><div class=\"account__wrapper\"><a target=\"_blank\" href=\"/Ministries\" title=\"Ministries\" to=\"/Ministries\" class=\"permalink account__display-name\"><div class=\"account__avatar-wrapper\"><div class=\"account__avatar\" style=\"width: 36px; height: 36px; background-image: url(&quot;https://gab.com/system/accounts/avatars/001/334/524/original/b45e0131e8a232f2.jpg?1579034309&quot;);\"></div></div><span class=\"display-name\"><bdi><strong class=\"display-name__html\">Ministries</strong></bdi><span class=\"display-name__account\">@Ministries</span></span></a><div class=\"account__relationship\"><button aria-label=\"Dismiss suggestion\" title=\"Dismiss suggestion\" class=\"icon-button\" tabindex=\"0\" style=\"font-size: 18px; width: 23.1429px; height: 23.1429px; line-height: 18px;\"><i role=\"img\" alt=\"times\" class=\"fa fa-times fa-fw\" aria-hidden=\"true\"></i></button></div></div></div>
$r.="</div></div></div><div class=\"getting-started__footer\"><ul><li><a href=\"#\"><span>Hotkeys</span></a> · </li><li><a href=\"/auth/edit\"><span>Security</span></a> · </li><li><a href=\"/about\"><span>About this server</span></a> · </li><li><a href=\"/settings/applications\"><span>Developers</span></a> · </li><li><a href=\"/about/tos\"><span>Terms of Service</span></a> · </li><li><a href=\"/about/dmca\"><span>DMCA</span></a> · </li><li><a href=\"/about/sales\"><span>Terms of Sale</span></a> · </li><li><a href=\"/about/privacy\"><span>Privacy Policy</span></a></li><li> ·&nbsp;<a href=\"/auth/sign_out\" data-method=\"delete\"><span>Logout</span></a></li></ul><p><span>Gab Social is open source software. You can contribute or report issues on GitLab at <span><a href=\"https://code.gab.com/gab/social/gab-social\" rel=\"noopener\" target=\"_blank\">gab/social/gab-social</a> (v2.8.4)</span>.</span></p><p>© 2019 Gab AI Inc.</p></div></div></div></div></div></div><div class=\"footer-bar\"><div class=\"footer-bar__container\"><a class=\"footer-bar__link\" data-preview-title-id=\"column.home\" aria-label=\"Home\" href=\"/home\"><i class=\"tabs-bar__link__icon home\"></i><span>Home</span></a><a class=\"footer-bar__link\" data-preview-title-id=\"column.notifications\" aria-label=\"Notifications\" href=\"/notifications\"><i class=\"tabs-bar__link__icon notifications\"></i><span>Notifications</span></a><a class=\"footer-bar__link\" data-preview-title-id=\"column.groups\" aria-label=\"Groups\" href=\"/groups\"><i class=\"tabs-bar__link__icon groups\"></i><span>Groups</span></a><a class=\"footer-bar__link footer-bar__link--trends\" href=\"https://trends.gab.com\" data-preview-title-id=\"tabs_bar.trends\" aria-label=\"tabs_bar.trends\"><i class=\"tabs-bar__link__icon trends\"></i><span>Trends</span></a></div></div><button class=\"floating-action-button\" aria-label=\"Publish\"></button><div class=\"notification-list\"></div><div></div><div class=\"modal-root\" style=\"opacity: 0;\"></div><div class=\"upload-area\" style=\"visibility: hidden; opacity: 0;\"><div class=\"upload-area__drop\"><div class=\"upload-area__background\" style=\"transform: scale(0.95);\"></div><div class=\"upload-area__content\"><span>Drag &amp; drop to upload</span></div></div></div><div class=\"sidebar-menu__root\"><div class=\"sidebar-menu__wrapper\" role=\"button\"></div><div class=\"sidebar-menu\"><div class=\"sidebar-menu-header\"><span class=\"sidebar-menu-header__title\">Account Info</span><button aria-label=\"close\" title=\"close\" class=\"sidebar-menu-header__btn icon-button\" tabindex=\"0\" style=\"font-size: 18px; width: 23.1429px; height: 23.1429px; line-height: 18px;\"><i role=\"img\" alt=\"close\" class=\"fa fa-close fa-fw\" aria-hidden=\"true\"></i></button></div><div class=\"sidebar-menu__content\"><div class=\"sidebar-menu-profile\"><div class=\"sidebar-menu-profile__name\"></div></div><div class=\"sidebar-menu__section\"><div class=\"wtf-panel progress-panel\"><div class=\"wtf-panel-header progress-panel-header\"><div class=\"wtf-panel-header__label\">Gab's Operational Expenses</div></div><div class=\"wtf-panel__content progress-panel__content\"><span class=\"progress-panel__text\">We are 100% funded by you.</span><div class=\"progress-panel__bar-container\"><a class=\"progress-panel__bar\" href=\"https://shop.dissenter.com/category/donations\" style=\"width: 31.3%;\"><span class=\"progress-panel__bar__text\">31.3% covered this month</span></a></div></div></div></div><a class=\"sidebar-menu-item\" href=\"https://pro.gab.com\"><i role=\"img\" alt=\"arrow-up\" class=\"fa fa-arrow-up fa-fw\"></i><span class=\"sidebar-menu-item__title\">Upgrade to GabPRO</span></a><a class=\"sidebar-menu-item\" href=\"https://shop.dissenter.com/category/donations\"><i role=\"img\" alt=\"heart\" class=\"fa fa-heart fa-fw\"></i><span class=\"sidebar-menu-item__title\">Make a Donation</span></a><a class=\"sidebar-menu-item\" href=\"https://shop.dissenter.com\"><i role=\"img\" alt=\"shopping-cart\" class=\"fa fa-shopping-cart fa-fw\"></i><span class=\"sidebar-menu-item__title\">Store - Buy Merch</span></a><a class=\"sidebar-menu-item\" href=\"https://trends.gab.com\"><i role=\"img\" alt=\"signal\" class=\"fa fa-signal fa-fw\"></i><span class=\"sidebar-menu-item__title\">Trends</span></a><a class=\"sidebar-menu-item\" href=\"/search\"><i role=\"img\" alt=\"search\" class=\"fa fa-search fa-fw\"></i><span class=\"sidebar-menu-item__title\">Search</span></a><a class=\"sidebar-menu-item\" href=\"/settings/preferences\"><i role=\"img\" alt=\"cog\" class=\"fa fa-cog fa-fw\"></i><span class=\"sidebar-menu-item__title\">Preferences</span></a></div><div class=\"sidebar-menu__section\"><div class=\"sidebar-menu-item\" role=\"button\"><i role=\"img\" alt=\"plus\" class=\"fa fa-plus fa-fw\"></i><span class=\"sidebar-menu-item__title\">More</span></div><div style=\"display: none;\"><a class=\"sidebar-menu-item\" href=\"/lists\"><i role=\"img\" alt=\"list\" class=\"fa fa-list fa-fw\"></i><span class=\"sidebar-menu-item__title\">Lists</span></a><a class=\"sidebar-menu-item\" href=\"/follow_requests\"><i role=\"img\" alt=\"user-plus\" class=\"fa fa-user-plus fa-fw\"></i><span class=\"sidebar-menu-item__title\">Follow requests</span></a><a class=\"sidebar-menu-item\" href=\"/blocks\"><i role=\"img\" alt=\"ban\" class=\"fa fa-ban fa-fw\"></i><span class=\"sidebar-menu-item__title\">Blocked users</span></a><a class=\"sidebar-menu-item\" href=\"/domain_blocks\"><i role=\"img\" alt=\"sitemap\" class=\"fa fa-sitemap fa-fw\"></i><span class=\"sidebar-menu-item__title\">Hidden domains</span></a><a class=\"sidebar-menu-item\" href=\"/mutes\"><i role=\"img\" alt=\"times-circle\" class=\"fa fa-times-circle fa-fw\"></i><span class=\"sidebar-menu-item__title\">Muted users</span></a><a class=\"sidebar-menu-item\" href=\"/filters\"><i role=\"img\" alt=\"filter\" class=\"fa fa-filter fa-fw\"></i><span class=\"sidebar-menu-item__title\">Muted words</span></a></div></div><div class=\"sidebar-menu__section\"><a class=\"sidebar-menu-item\" href=\"/auth/sign_out\" data-method=\"delete\"><span class=\"sidebar-menu-item__title\">Logout</span></a></div></div></div></div></div></div></div><div style=\"display: none\"><svg version=\"1.0\" xmlns=\"http://www.w3.org/2000/svg\" width=\"1024pt\" height=\"1024pt\" viewBox=\"0 0 1024 1024\" preserveAspectRatio=\"xMidYMid meet\"><g transform=\"translate(0,1024) scale(0.1,-0.1)\" fill=\"#000000\" stroke=\"none\"><path d=\"M4426 9444 c-367 -49 -753 -185 -1056 -373 -230 -143 -537 -422 -707 -641 -321 -416 -536 -930 -608 -1450 -22 -161 -30 -497 -16 -645 45 -464 167 -878 363 -1230 152 -274 286 -450 495 -656 243 -238 443 -384 703 -513 713 -353 1546 -364 2209 -31 191 96 328 195 510 370 l94 90 -7 -216 c-12 -402 -48 -608 -148 -848 -121 -291 -295 -503 -543 -663 -144 -93 -248 -136 -451 -187 -177 -43 -237 -51 -406 -52 -243 -1 -443 32 -688 116 -198 68 -500 217 -642 317 l-27 18 -55 -102 c-30 -57 -194 -364 -364 -683 l-309 -580 66 -49 c465 -344 1044 -564 1716 -651 192 -25 749 -17 955 14 499 74 920 213 1270 421 338 199 652 502 862 830 299 468 468 990 545 1690 15 142 17 389 20 2848 l4 2692 -911 0 -910 0 0 -262 0 -261 -112 109 c-124 121 -190 173 -319 257 -239 154 -518 255 -859 313 -136 23 -530 28 -674 8z m889 -1634 c86 -16 268 -77 350 -118 177 -88 365 -246 475 -399 208 -290 283 -656 204 -991 -120 -510 -532 -903 -1034 -987 -121 -21 -341 -16 -453 10 -180 40 -387 143 -543 269 -293 236 -463 587 -465 961 -1 277 104 562 300 808 173 218 497 406 779 452 96 16 286 13 387 -5z\"></path></g></svg></div></div></body></head>";

}//endif
else if ($a==="gab.com") {
$c=$d.$c;
$r.="<html lang='en'>
<base href=\"https://gab.com\">
<head>
<meta charset='utf-8'>
<meta content='width=device-width, initial-scale=1' name='viewport' viewport-fit='cover'>
<link href='/favicon.ico' rel='icon' type='image/x-icon'>
<link href='/apple-touch-icon.png' rel='apple-touch-icon' sizes='180x180'>
<link color='#2B90D9' href='/mask-icon.svg' rel='mask-icon'>
<link href='/manifest.json' rel='manifest'>
<meta content='/browserconfig.xml' name='msapplication-config'>
<meta content='#282c37' name='theme-color'>
<meta content='yes' name='apple-mobile-web-app-capable'>";
$ax=$c;
$ax=substr($ax,strpos($ax,",\"account\":\"")+13);
$ax=substr($ax,strpos($ax,"\",\"acct\":\"")+10);
$an=substr($ax,0,strpos($ax,",")-1);
$ax=substr($ax,strpos($ax,"\",\"display_name\":\"")+18);
$dn=substr($ax,0,strpos($ax,",")-1);

$r.="\n<title>".$dn." (@".$an."@gab.com) | gab.com - Gab Social</title>
<link rel=\"stylesheet\" media=\"all\" href=\"/packs/css/common-05893041.css\">
<link rel=\"stylesheet\" media=\"all\" href=\"/packs/css/default-2ae0f3ee.chunk.css\">
<script src=\"/packs/js/common-e73150f4876844f8c1d1.js\" crossorigin=\"anonymous\"></script>
<script src=\"/packs/js/locale_en-4880512ce1277988e28c.chunk.js\" crossorigin=\"anonymous\"></script>
<link rel=\"preload\" href=\"/packs/js/features/getting_started-e5d7920a8ba5d9a852be.chunk.js\" as=\"script\" type=\"text/javascript\" crossorigin=\"anonymous\">
<link rel=\"preload\" href=\"/packs/js/features/compose-967522b61d9c20fad72a.chunk.js\" as=\"script\" type=\"text/javascript\" crossorigin=\"anonymous\">
<link rel=\"preload\" href=\"/packs/js/features/home_timeline-922306e55154034b166d.chunk.js\" as=\"script\" type=\"text/javascript\" crossorigin=\"anonymous\">
<link rel=\"preload\" href=\"/packs/js/features/notifications-686a1ea14667ef2fe673.chunk.js\" as=\"script\" type=\"text/javascript\" crossorigin=\"anonymous\">";
$ax=$e;
$ax=substr($ax,strpos($ax,"\",\"note\":")+8);
$content=substr($ax,2,strpos($ax,",\"url\":\"")-3);
$content=str_replace("\\u003c","<",$content);
$content=str_replace("\\u003e",">",$content);
$content=str_replace("\u0026apos;","'",$content);
$content=str_replace("\u0026quot;","\"",$content);


$ax=substr($ax,strpos($ax,"\",\"followers_count\":")+19);
$rep=substr($ax,1,strpos($ax,","));
//$r.="<br>";
$ax=substr($ax,strpos($ax,",\"following_count\":")+19);
$rbc=substr($ax,0,strpos($ax,","));
//echo"<br>";
$ax=substr($ax,strpos($ax,",\"status_count\":")+20);
$fv=substr($ax,0,strpos($ax,","));
$rep2=$rep;
$rbc2=$rbc;
$fv2=$fv;
//echo $fv;
//$fv=str_replace(",","",$fv);
if ($rep>1000) {$rep=(int)($rep/100);$rep=$rep/10;$rep3=$rep;$rep.="k";}
if ($rbc>1000) {$rbc=(int)($rbc/100);$rbc=$rbc/10;$rbc3=$rbc;$rbc.="k";}
if ($fv>1000) {$fv=(int)($fv/100);$fv=$fv/10;$fv3=$fv;$fv.="k";}

$r.="\n<meta content='".$fv." Gabs, ".$rbc." Following, ".$rep." Followers\" · ".strip_tags($content)."' name=\"description\">
<link href=\"https://gab.com/api/salmon/76559\" rel=\"salmon\">";
$r.="\n<link href=\"https://gab.com/users\"".$an.".atom\" rel=\"alternate\" type=\"application/atom+xml\">";
$r.="\n<link href=\"https://gab.com/users\"".$an.".rss\" rel=\"alternate\" type=\"application/rss+xml\">
<link href=\"https://gab.com/users\"".$an."\" rel=\"alternate\" type=\"application/activity+json\">
<meta content=\"profile\" property=\"og:type\">
<meta content=\"".$fv." Gabs, ".$rbc." Following, ".$rep." Followers · ".strip_tags($content)." name=\"description\">
<meta content=\"https://gab.com/".$an."\" property=\"og:url\"><meta content=\"Gab Social\" property=\"og:site_name\">
<meta content=\"".$dn." (@".$an."@gab.com) | gab.com\" property=\"og:title\">
<meta content=\"".$fv." Gabs, ".$rbc." Following, ".$rep." Followers · ".strip_tags($content)." property=\"og:description\">";
$ax=$e;
$ax=substr($ax,strpos($ax,"\",\"avatar\":")+11);
$av=substr($ax,0,strpos($ax,","));
$r.="\n<meta content=\"".$av." property=\"og:image\">
<meta content=\"120\" property=\"og:image:width\">
<meta content=\"120\" property=\"og:image:height\">
<meta content=\"summary\" property=\"twitter:card\">
<meta content=\"".$an."@gab.com\" property=\"profile:username\">
<script charset=\"utf-8\" src=\"/packs/js/features/account_timeline-c55104bcc33001d27555.chunk.js\"></script>
</head>";

$r.="\n<body class=\"app-body theme-default no-reduce-motion\">
<div class=\"app-holder\" data-props=\"{&quot;locale&quot;:&quot;en&quot;}\" id=\"gabsocial\">
<div tabindex=\"-1\">
<div class=\"ui\">
<nav class=\"tabs-bar logged-in\">
<div class=\"tabs-bar__container\">
<div class=\"tabs-bar__split tabs-bar__split--left\">
<a class=\"tabs-bar__link--logo\" data-preview-title-id=\"column.home\" aria-label=\"Home\" href=\"/home\" style=\"padding: 0px;\">
<span>Home</span></a><a class=\"tabs-bar__link\" data-preview-title-id=\"column.home\" aria-label=\"Home\" href=\"/home\"><i class=\"tabs-bar__link__icon home\"></i><span>Home</span></a><a class=\"tabs-bar__link\" data-preview-title-id=\"column.notifications\" aria-label=\"Notifications\" href=\"/notifications\"><i class=\"tabs-bar__link__icon notifications\"></i><span>Notifications</span></a><a class=\"tabs-bar__link\" data-preview-title-id=\"column.groups\" aria-label=\"Groups\" href=\"/groups\"><i class=\"tabs-bar__link__icon groups\"></i><span>Groups</span></a><a class=\"tabs-bar__link tabs-bar__link--trends\" href=\"https://trends.gab.com\" data-preview-title-id=\"tabs_bar.trends\" aria-label=\"tabs_bar.trends\"><i class=\"tabs-bar__link__icon trends\"></i><span>Trends</span></a></div><div class=\"tabs-bar__split tabs-bar__split--right\"><div class=\"tabs-bar__search-container\"><div class=\"search\"><label><span style=\"display: none;\">Search</span><input class=\"search__input\" type=\"text\" placeholder=\"Search\" value=\"\"></label><div role=\"button\" tabindex=\"0\" class=\"search__icon\"><i role=\"img\" alt=\"search\" class=\"fa fa-search active\"></i><i role=\"img\" alt=\"times-circle\" class=\"fa fa-times-circle\" aria-label=\"Search\"></i></div></div></div><div class=\"flex\"><div class=\"tabs-bar__profile\">";
$r.="\n<button class=\"tabs-bar__sidebar-btn\"></button><div class=\"compose__action-bar\" style=\"margin-top: -6px;\"><div class=\"compose__action-bar-dropdown\"><div><button aria-label=\"Menu\" title=\"Menu\" class=\"icon-button\" tabindex=\"0\" style=\"font-size: 34px; width: 43.7143px; height: 43.7143px; line-height: 34px;\"><i role=\"img\" alt=\"chevron-down\" class=\"fa fa-chevron-down fa-fw\" aria-hidden=\"true\"></i></button></div></div></div></div><span class=\"tabs-bar__page-name\">Profile</span><button class=\"tabs-bar__button-compose button\" aria-label=\"Gab\"><span>Gab</span></button><a class=\"tabs-bar__search-btn\" href=\"/search\"><i class=\"tabs-bar__link__icon tabs-bar__link__icon--search\"></i><span>Search</span></a></div></div></div></nav><div class=\"page\"><div class=\"page__top\"><div class=\"account-timeline__header\"><div class=\"account__header\"><div class=\"account__header__image\"><div class=\"account__header__info\"></div><img src=\"https://gab.com/media/user/5b7ad6f188e51.jpeg\" alt=\"\" class=\"parallax\"></div><div class=\"account__header__bar\"><div class=\"account__header__extra\"><div class=\"account__header__avatar\">";
$r.="\n<div class=\"account__avatar\" style=\"width: 200px; height: 200px; background-image: url(&quot;https://gab.com/system/accounts/avatars/000/076/559/original/087a1b6017e25272.jpg?1565823644&quot;);\"></div></div><div class=\"account__header__extra__links\">";
$r.="\n<a class=\"active\" aria-current=\"page\" title=\"".$fv3."\" href=\"".$an."\">";
$r.="\n<span><span>".$fv3.(strpos($fv,"k")>0?"</span>K</span>":"")."<span>Gabs</span></a>";
$r.="\n<a title=\"".$rbc2."\" href=\"/".$an."/following\">";
$r.="\n<span>".$rbc3.(strpos($rbc,"k")?"</span>K</span>":"")."</span><span>Follows</span></a>";
$r.="\n<a title=\"".$rep2."\" href=\"/".$an."/followers\">";
$r.="\n<span><span>".$rep3.(strpos($rep,"k")?"</span>K</span>":"")."<span>Followers</span></a></div><div class=\"account__header__extra__buttons\"><button class=\"button logo-button button--destructive\" style=\"padding: 0px 16px; height: 36px; line-height: 36px;\">Unfollow</button><button class=\"button button button-alternative-2\" style=\"padding: 0px 16px; height: 36px; line-height: 36px;\"><span>Mention</span></button><div>";
$r.="\n<button aria-label=\"Menu\" title=\"Menu\" class=\"icon-button\" tabindex=\"0\" style=\"font-size: 24px; width: 30.8571px; height: 30.8571px; line-height: 24px;\"><i role=\"img\" alt=\"ellipsis-v\" class=\"fa fa-ellipsis-v fa-fw\" aria-hidden=\"true\"></i></button></div></div></div></div></div></div></div><div class=\"page__columns\">
<div class=\"columns-area__panels\"><div class=\"columns-area__panels__pane columns-area__panels__pane--left\">";
$r.="\n<div class=\"columns-area__panels__pane__inner\"><div class=\"profile-info-panel\"><div class=\"profile-info-panel__content\">";
$r.="\n<div class=\"profile-info-panel-content__name\"><h1>
<span>".$dn."</span><span class=\"verified-icon\">
<span class=\"visuallyhidden\">Verified Account</span></span><small>@".$an." </small></h1></div>";
$r.="\n<div class=\"profile-info-panel-content__badges\"><div class=\"profile-info-panel-content__badges__join-date\"><i role=\"img\" alt=\"calendar\" class=\"fa fa-calendar\"></i>
<span>Member since November 2016</span></div></div>";
$r.="\n<div class=\"profile-info-panel-content__bio\"> ".$content."</a></p></div></div></div></div></div><div class=\"columns-area__panels__main\"><div class=\"columns-area columns-area--mobile\"><div role=\"region\" class=\"column\"><button class=\"column-back-button\">
<i role=\"img\" alt=\"chevron-left\" class=\"fa fa-chevron-left column-back-button__icon fa-fw\"></i><span>Back</span></button><div class=\"account__section-headline\"><div style=\"width: 100%; display: flex;\">
<a class=\"active\" aria-current=\"page\" href=\"/".$an."\">
<span>Gabs</span></a><a href=\"/".$an."/with_replies\"><span>Gabs and replies</span></a>
<a href=\"/".$an."/media\"><span>Media</span></a></div></div>
<div class=\"timeline-queue-header hidden\">
<a class=\"timeline-queue-header__btn\"></a></div>
<div class=\"slist\">";
$r.="<div role=\"feed\" class=\"item-list\">";

//begin flag.
$ex=explode("\",\"created_at\":\"",$c);
$cc=count($ex);
$ix=explode("\",\"created_at\":\"",$d);
$dd=count($ix);
$rbd=false;
for($i=0;$i<$cc;$i++){
$ax=$ex[$i];
if (!(strpos($ax,",\"card\":")!==FALSE)) {$rbd=true;}
else {
$time=substr($ax,0,strpos($ax,"."))."+0000";
new DateTimeZone('UTC');
$time=Date("U",strtoTime($time));
$ax=substr($ax,strpos($ax,",\"in_reply_to_id\":\"")+18);
$r2=substr($ax,1,strpos($ax,",")-2);
$ax=substr($ax,strpos($ax,"\",\"uri\":")+9);
$uri=substr($ax,0,strpos($ax,",")-1);
$ax=substr($ax,strpos($ax,"\",\"replies_count\":")+18);
$rep=substr($ax,0,strpos($ax,","));
$ax=substr($ax,strpos($ax,",\"reblogs_count\":")+17);
$rbc=substr($ax,0,strpos($ax,","));
$ax=substr($ax,strpos($ax,",\"favourites_count\":")+20);
$fv=substr($ax,0,strpos($ax,","));
$ax=substr($ax,strpos($ax,",\"quote_of_id\":")+15);
$quoted=substr($ax,0,strpos($ax,",\"favourited\":"));
$ax=substr($ax,strpos($ax,",\"content\":\"")+12);
$content=substr($ax,0,strpos($ax,"\",\"reblog")-1);
$content=str_replace("\\u003c","<",$content);
$content=str_replace("\\u003e",">",$content);
$content=str_replace("\u0026apos;","'",$content);
$content=str_replace("\u0026quot;","\"",$content);
$ax=substr($ax,strpos($ax,",\"account\":\"")+13);
$ax=substr($ax,strpos($ax,":{\"id\":\"")+8);
$id=substr($ax,0,strpos($ax,",")-1);
$ax=substr($ax,strpos($ax,"\",\"username\":\"")+14);
$usn=substr($ax,0,strpos($ax,",")-1);
$ax=substr($ax,strpos($ax,"\",\"acct\":\"")+10);
$account=substr($ax,0,strpos($ax,",")-1);
$ax=substr($ax,strpos($ax,"\",\"display_name\":\"")+18);
$dn=substr($ax,0,strpos($ax,",")-1);
$ax=substr($ax,strpos($ax,"\",\"avatar\":\"")+12);
$pic=substr($ax,0,strpos($ax,",")-1);
$ax=substr($ax,strpos($ax,",\"is_pro\":")+10);
$ipro=substr($ax,0,strpos($ax,","));
$ax=substr($ax,strpos($ax,",\"is_verified\":")+15);
$iv=substr($ax,0,strpos($ax,","));
$ax=substr($ax,strpos($ax,",\"is_donor\":")+12);
$isd=substr($ax,0,strpos($ax,","));
$ma="[]";
$ma2=$ma;
//$mu="";
$ax=substr($ax,strpos($ax,",\"media_attachments\":")+21);
$ma=substr($ax,0,strpos($ax,","));
if (substr($ma,0,2)==="[]") {$ma="[]";}
else{
$ax=substr($ax,strpos($ax,"\"video\",\"url\":")+15);
$ma=substr($ax,0,strpos($ax,",")-1);
$ma2=str_replace("/original/","/small/",$ma);
}


$ax=substr($ax,strpos($ax,",\"card\":")+8);
$card=substr($ax,0,strpos($ax,"}"));
$card=substr($card,strpos($card,"{\"url\":")+6);
$url=substr($card,0,strpos($card,","));
$card=substr($card,strpos($card,",\"title\":")+10);
$title=substr($card,0,strpos($card,",")-1);
$card=substr($card,strpos($card,",\"description\":")+16);
$desc=substr($card,0,strpos($card,",")-1);
$card=substr($card,strpos($card,",\"provider_url\":")+17);
$purl=substr($card,0,strpos($card,",")-1);
$card=substr($card,strpos($card,",\"image\":")+10);
$image=substr($card,0,strpos($card,",")-1);
$ax=substr($ax,strpos($ax,"{\"id\":")+6);
$id2=substr($ax,0,strpos($ax,",")-1);

$r.="<article aria-posinset=\"".($i+1)."\" aria-setsize=\"21\" data-id=\"f-103518736815382116\" tabindex=\"0\"><div tabindex=\"-1\">
<div class=\"status__wrapper status__wrapper-public focusable\" tabindex=\"0\" data-featured=\"true\"";
$r.=" aria-label=\"".$account.",".strip_tags($content);
$r.=",".date("F j, y, g:i a" ,$time).",".$account."\">";
if ($i<$dd) {
$r.="<div class=\"status__prepend\">";
$r.="<div class=\"status__prepend-icon-wrapper\"><i role=\"img\" alt=\"thumb-tack\" class=\"fa fa-thumb-tack status__prepend-icon fa-fw\"></i></div><span>Pinned gab</span></div>";}
$r.="<div class=\"status status-public\" data-id=\"".$id2."\">
<div class=\"status__expand\" role=\"presentation\"></div>
<div class=\"status__info\">
<a class=\"status__relative-time\" href=\"/".$account."/posts/".$id2."\">";

$diff=time()-$time;
$dt="";
if ($diff>60) {$dt=(int)($diff/60);$dt.="m";}
if ($diff>3600) {$dt=(int)($diff/3600);$dt.="h";}
if ($diff>3600*24) {$dt=(int)($diff/(60*60*24));$dt.="d";}
if ($diff>3600*24*7) {$dt=(int)($diff/(60*60*24*7));$dt.="w";}
if ($diff>3600*24*30) {$dt=(int)($diff/(60*60*24*30));$dt.="M";}
if ($diff>3600*24*365) {$dt=(int)$diff/((60*60*24*365));$dt.="Y";}


$r.="<time datetime=\"".$time."\" title=\"".$time."\">".$dt."</time></a>
<a class=\"status__display-name active\" aria-current=\"page\" title=\"".$account."\" href=\"/".$account."\">
<div class=\"status__avatar\">
<div class=\"account__avatar\" style=\"width: 48px; height: 48px; background-image: url(&quot;".$pic."&quot;)\"></div></div>
<span class=\"display-name\"><bdi>
<strong class=\"display-name__html\">".$dn."</strong></bdi>";
if ($iv==="true") {$r.="<span class=\"verified-icon\"><span class=\"visuallyhidden\">Verified Account</span></span>";}

//replace :text: with emojis
$r.="<span class=\"display-name__account\">@".$account."</span></span></a></div>
<div tabindex=\"0\" class=\"status__content status__content--with-action\" lang=\"en\" style=\"direction: ltr;\">".$content."</div>";
if ($ma==="[]") {
$r.="<a href=\"".$url."\" class=\"status-card\" target=\"_blank\" rel=\"noopener\">
<div class=\"status-card__image\">
<div class=\"status-card__image-image\" style=\"background-image: url(&quot;".$image."&quot;);\"></div></div>
<div class=\"status-card__content\">
<strong class=\"status-card__title\" title=\"".$title."\">".$title."</strong>
<p class=\"status-card__description\">".$desc."</p>
<span class=\"status-card__host\">
<i role=\"img\" alt=\"link\" class=\"fa fa-link fa-fw\"></i> ".$purl."</span></div></a>";
}
else {
$r.="<div role=\"menuitem\" class=\"video-player inline\" tabindex=\"0\" style=\"height: 285px;\">
<canvas width=\"32\" height=\"32\" class=\"media-gallery__preview media-gallery__preview--hidden\"></canvas>
<video playsinline=\"\" src=\"".$ma."\" poster=\"".$ma2."\" preload=\"none\" loop=\"\" role=\"button\" tabindex=\"0\" width=\"507\" height=\"285\" volume=\"1\">
</video>
<div class=\"spoiler-button spoiler-button--hidden\"><button type=\"button\" class=\"spoiler-button__overlay\">
<span class=\"spoiler-button__overlay__label\">
<span>Media hidden</span></span></button></div>
<div class=\"video-player__controls active\">
<div class=\"video-player__seek\"><div class=\"video-player__seek__buffer\"></div>
<div class=\"video-player__seek__progress\"></div>
<span class=\"video-player__seek__handle\" tabindex=\"0\">
</span></div>";
$r.="<div class=\"video-player__buttons-bar\">
<div class=\"video-player__buttons left\">
<button type=\"button\" aria-label=\"Play\">
<i role=\"img\" alt=\"play\" class=\"fa fa-play fa-fw\"></i></button>";
$r.="<button type=\"button\" aria-label=\"Mute sound\">
<i role=\"img\" alt=\"volume-up\" class=\"fa fa-volume-up fa-fw\"></i></button>";
$r.="<div class=\"video-player__volume\">
<div class=\"video-player__volume__current\" style=\"width: 50px;\"></div>
<span class=\"video-player__volume__handle\" tabindex=\"0\" style=\"left: 110px;\"></span></div></div>";
$r.="<div class=\"video-player__buttons right\">
<button type=\"button\" aria-label=\"Hide video\">
<i role=\"img\" alt=\"eye-slash\" class=\"fa fa-eye-slash fa-fw\"></i></button>";
$r.="<button type=\"button\" aria-label=\"Expand video\">
<i role=\"img\" alt=\"expand\" class=\"fa fa-expand fa-fw\"></i></button>";
$r.="<button type=\"button\" aria-label=\"Full screen\">
<i role=\"img\" alt=\"arrows-alt\" class=\"fa fa-arrows-alt fa-fw\"></i></button></div></div></div></div>";

}
//aftermovie
$r.="<div class=\"status__action-bar\">
<div class=\"status__action-bar__counter\">
<button aria-label=\"Reply\" title=\"Reply\" class=\"status__action-bar-button icon-button\" tabindex=\"0\" style=\"font-size: 18px; width: 23.1429px; height: 23.1429px; line-height: 18px;\">
<i role=\"img\" alt=\"reply\" class=\"fa fa-reply fa-fw\" aria-hidden=\"true\"></i></button>
<a class=\"detailed-status__link\" href=\"/".$account."/posts/".$id2."\">".$rep."</a></div>
<div class=\"status__action-bar__counter\">
<button aria-label=\"Repost\" aria-pressed=\"false\" title=\"Repost\" class=\"status__action-bar-button icon-button\" tabindex=\"0\" style=\"font-size: 18px; width: 23.1429px; height: 23.1429px; line-height: 18px;\">
<i role=\"img\" alt=\"retweet\" class=\"fa fa-retweet fa-fw\" aria-hidden=\"true\"></i></button>
<a class=\"detailed-status__link\" href=\"/".$account."/posts/".$id2."/reblogs\">".$rgc."</a></div>
<div class=\"status__action-bar__counter\">
<button aria-label=\"Quote\" title=\"Quote\" class=\"status__action-bar-button icon-button\" tabindex=\"0\" style=\"font-size: 18px; width: 23.1429px; height: 23.1429px; line-height: 18px;\">
<i role=\"img\" alt=\"quote-left\" class=\"fa fa-quote-left fa-fw\" aria-hidden=\"true\"></i></button></div>
<div class=\"status__action-bar__counter\">
<button aria-label=\"Favorite\" aria-pressed=\"false\" title=\"Favorite\" class=\"status__action-bar-button star-icon icon-button\" tabindex=\"0\" style=\"font-size: 18px; width: 23.1429px; height: 23.1429px; line-height: 18px;\">
<i role=\"img\" alt=\"star\" class=\"fa fa-star fa-fw\" aria-hidden=\"true\" style=\"transform: rotate(0deg);\"></i></button>
<span class=\"detailed-status__link\">".$fv."</span></div>
<div class=\"status__action-bar-dropdown\"><div>
<button aria-label=\"More\" title=\"More\" class=\"icon-button\" tabindex=\"0\" style=\"font-size: 18px; width: 23.1429px; height: 23.1429px; line-height: 18px;\">
<i role=\"img\" alt=\"ellipsis-h\" class=\"fa fa-ellipsis-h fa-fw\" aria-hidden=\"true\"></i></button></div></div></div></div></div></div></article>";
$rbd=false;
}}//endelsendfor

//do once;
$r.="<button class=\"load-more\" style=\"visibility: visible;\"><span>Load more</span></button></div></div></div></div></div><div class=\"columns-area__panels__pane columns-area__panels__pane--right\"><div class=\"columns-area__panels__pane__inner\"><div class=\"wtf-panel\"><div class=\"wtf-panel-header\"><i role=\"img\" alt=\"users\" class=\"fa fa-users wtf-panel-header__icon\"></i><span class=\"wtf-panel-header__label\"><span>Who To Follow</span></span><div class=\"getting-started__footer\"><ul><li><a href=\"#\"><span>Hotkeys</span></a> · </li><li><a href=\"/auth/edit\"><span>Security</span></a> · </li><li><a href=\"/about\"><span>About this server</span></a> · </li><li><a href=\"/settings/applications\"><span>Developers</span></a> · </li><li><a href=\"/about/tos\"><span>Terms of Service</span></a> · </li><li><a href=\"/about/dmca\"><span>DMCA</span></a> · </li><li><a href=\"/about/sales\"><span>Terms of Sale</span></a> · </li><li><a href=\"/about/privacy\"><span>Privacy Policy</span></a></li><li> ·&nbsp;<a href=\"/auth/sign_out\" data-method=\"delete\"><span>Logout</span></a></li></ul><p><span>Gab Social is open source software. You can contribute or report issues on GitLab at <span><a href=\"https://code.gab.com/gab/social/gab-social\" rel=\"noopener\" target=\"_blank\">gab/social/gab-social</a> (v2.8.4)</span>.</span></p><p>© 2019 Gab AI Inc.</p></div></div></div></div></div></div><div class=\"footer-bar\"><div class=\"footer-bar__container\"><a class=\"footer-bar__link\" data-preview-title-id=\"column.home\" aria-label=\"Home\" href=\"/home\"><i class=\"tabs-bar__link__icon home\"></i><span>Home</span></a><a class=\"footer-bar__link\" data-preview-title-id=\"column.notifications\" aria-label=\"Notifications\" href=\"/notifications\"><i class=\"tabs-bar__link__icon notifications\"></i><span>Notifications</span></a><a class=\"footer-bar__link\" data-preview-title-id=\"column.groups\" aria-label=\"Groups\" href=\"/groups\"><i class=\"tabs-bar__link__icon groups\"></i><span>Groups</span></a><a class=\"footer-bar__link footer-bar__link--trends\" href=\"https://trends.gab.com\" data-preview-title-id=\"tabs_bar.trends\" aria-label=\"tabs_bar.trends\"><i class=\"tabs-bar__link__icon trends\"></i><span>Trends</span></a></div></div><button class=\"floating-action-button\" aria-label=\"Publish\"></button><div class=\"notification-list\"></div><div></div><div class=\"modal-root\" style=\"opacity: 0;\"></div><div class=\"upload-area\" style=\"visibility: hidden; opacity: 0;\"><div class=\"upload-area__drop\"><div class=\"upload-area__background\" style=\"transform: scale(0.95);\"></div><div class=\"upload-area__content\"><span>Drag &amp; drop to upload</span></div></div></div><div class=\"sidebar-menu__root\"><div class=\"sidebar-menu__wrapper\" role=\"button\"></div><div class=\"sidebar-menu\"><div class=\"sidebar-menu-header\"><span class=\"sidebar-menu-header__title\">Account Info</span><button aria-label=\"close\" title=\"close\" class=\"sidebar-menu-header__btn icon-button\" tabindex=\"0\" style=\"font-size: 18px; width: 23.1429px; height: 23.1429px; line-height: 18px;\"><i role=\"img\" alt=\"close\" class=\"fa fa-close fa-fw\" aria-hidden=\"true\"></i></button></div><div class=\"sidebar-menu__section\"><div class=\"wtf-panel progress-panel\"><div class=\"wtf-panel-header progress-panel-header\"><div class=\"wtf-panel-header__label\">Gab's Operational Expenses</div></div><div class=\"wtf-panel__content progress-panel__content\"><span class=\"progress-panel__text\">We are 100% funded by you.</span><div class=\"progress-panel__bar-container\"><a class=\"progress-panel__bar\" href=\"https://shop.dissenter.com/category/donations\" style=\"width: 35%;\"><span class=\"progress-panel__bar__text\">35% covered this month</span></a></div></div></div></div><div class=\"sidebar-menu__section sidebar-menu__section--borderless\"><a class=\"sidebar-menu-item\" href=\"https://pro.gab.com\"><i role=\"img\" alt=\"arrow-up\" class=\"fa fa-arrow-up fa-fw\"></i><span class=\"sidebar-menu-item__title\">Upgrade to GabPRO</span></a><a class=\"sidebar-menu-item\" href=\"https://shop.dissenter.com/category/donations\"><i role=\"img\" alt=\"heart\" class=\"fa fa-heart fa-fw\"></i><span class=\"sidebar-menu-item__title\">Make a Donation</span></a><a class=\"sidebar-menu-item\" href=\"https://shop.dissenter.com\"><i role=\"img\" alt=\"shopping-cart\" class=\"fa fa-shopping-cart fa-fw\"></i><span class=\"sidebar-menu-item__title\">Store - Buy Merch</span></a><a class=\"sidebar-menu-item\" href=\"https://trends.gab.com\"><i role=\"img\" alt=\"signal\" class=\"fa fa-signal fa-fw\"></i><span class=\"sidebar-menu-item__title\">Trends</span></a><a class=\"sidebar-menu-item\" href=\"/search\"><i role=\"img\" alt=\"search\" class=\"fa fa-search fa-fw\"></i><span class=\"sidebar-menu-item__title\">Search</span></a><a class=\"sidebar-menu-item\" href=\"/settings/preferences\"><i role=\"img\" alt=\"cog\" class=\"fa fa-cog fa-fw\"></i><span class=\"sidebar-menu-item__title\">Preferences</span></a></div><div class=\"sidebar-menu__section\"><div class=\"sidebar-menu-item\" role=\"button\"><i role=\"img\" alt=\"plus\" class=\"fa fa-plus fa-fw\"></i><span class=\"sidebar-menu-item__title\">More</span></div><div style=\"display: none;\"><a class=\"sidebar-menu-item\" href=\"/lists\"><i role=\"img\" alt=\"list\" class=\"fa fa-list fa-fw\"></i><span class=\"sidebar-menu-item__title\">Lists</span></a><a class=\"sidebar-menu-item\" href=\"/follow_requests\"><i role=\"img\" alt=\"user-plus\" class=\"fa fa-user-plus fa-fw\"></i><span class=\"sidebar-menu-item__title\">Follow requests</span></a><a class=\"sidebar-menu-item\" href=\"/blocks\"><i role=\"img\" alt=\"ban\" class=\"fa fa-ban fa-fw\"></i><span class=\"sidebar-menu-item__title\">Blocked users</span></a><a class=\"sidebar-menu-item\" href=\"/domain_blocks\"><i role=\"img\" alt=\"sitemap\" class=\"fa fa-sitemap fa-fw\"></i><span class=\"sidebar-menu-item__title\">Hidden domains</span></a><a class=\"sidebar-menu-item\" href=\"/mutes\"><i role=\"img\" alt=\"times-circle\" class=\"fa fa-times-circle fa-fw\"></i><span class=\"sidebar-menu-item__title\">Muted users</span></a><a class=\"sidebar-menu-item\" href=\"/filters\"><i role=\"img\" alt=\"filter\" class=\"fa fa-filter fa-fw\"></i><span class=\"sidebar-menu-item__title\">Muted words</span></a></div></div><div class=\"sidebar-menu__section\"><a class=\"sidebar-menu-item\" href=\"/auth/sign_out\" data-method=\"delete\"><span class=\"sidebar-menu-item__title\">Logout</span></a></div></div></div></div></div></div></div><div style=\"display: none\"><svg version=\"1.0\" xmlns=\"http://www.w3.org/2000/svg\" width=\"1024pt\" height=\"1024pt\" viewBox=\"0 0 1024 1024\" preserveAspectRatio=\"xMidYMid meet\"><g transform=\"translate(0,1024) scale(0.1,-0.1)\" fill=\"#000000\" stroke=\"none\"><path d=\"M4426 9444 c-367 -49 -753 -185 -1056 -373 -230 -143 -537 -422 -707 -641 -321 -416 -536 -930 -608 -1450 -22 -161 -30 -497 -16 -645 45 -464 167 -878 363 -1230 152 -274 286 -450 495 -656 243 -238 443 -384 703 -513 713 -353 1546 -364 2209 -31 191 96 328 195 510 370 l94 90 -7 -216 c-12 -402 -48 -608 -148 -848 -121 -291 -295 -503 -543 -663 -144 -93 -248 -136 -451 -187 -177 -43 -237 -51 -406 -52 -243 -1 -443 32 -688 116 -198 68 -500 217 -642 317 l-27 18 -55 -102 c-30 -57 -194 -364 -364 -683 l-309 -580 66 -49 c465 -344 1044 -564 1716 -651 192 -25 749 -17 955 14 499 74 920 213 1270 421 338 199 652 502 862 830 299 468 468 990 545 1690 15 142 17 389 20 2848 l4 2692 -911 0 -910 0 0 -262 0 -261 -112 109 c-124 121 -190 173 -319 257 -239 154 -518 255 -859 313 -136 23 -530 28 -674 8z m889 -1634 c86 -16 268 -77 350 -118 177 -88 365 -246 475 -399 208 -290 283 -656 204 -991 -120 -510 -532 -903 -1034 -987 -121 -21 -341 -16 -453 10 -180 40 -387 143 -543 269 -293 236 -463 587 -465 961 -1 277 104 562 300 808 173 218 497 406 779 452 96 16 286 13 387 -5z\"></path></g></svg></div></body></html>";


}//end gab
else if ($a==="imgur.com")  {
if (strpos($b,"/t/")>0) {
$str=substr($b,strpos($b,".com")+4);
if (substr($str,0,1)==="/") {$str=substr($str,1);}
if (substr($str,strlen($str)-1,1)==="/") {$str=substr($str,0,strlen($str)-1);}
$cc=substr_count($str,"/");
if ($cc==1) {
$str="";
//return $c;
$str=substr($c,strrpos($c,"=")+2);
$str=substr($str,0,strpos($str,">")-1);
//echo "<br>";
$d=templatehelp($str);
$str=substr($d,strpos($d,".com\",a=\"")+9);
$str=substr($str,0,strpos($str,"\""));
$sstr="https://api.imgur.com/3/gallery";

$path=substr($b,strpos($b,"/t/"));


$sstr.=$path."/viral/week/0?client_id=".$str;
$e=templatehelp($sstr);

$pre=substr($c,0,strrpos($c,"</div>"));
$post="</div></body></html>";
$insert="<div><div class=\"desktop-app App\"><div>";
$insert.="<div class=\"App-cover NewCover TagsCover\" style=\"top: 0px; min-height: 451px; background-image: linear-gradient(rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.3) 20%), url(&quot;https://i.imgur.com/CXErE2G.jpg&quot;);\"><div class=\"MoveContainer Navbar HomeNavbar\" style=\"top: 0px;\"><div class=\"NavbarMenuButton\"><div class=\"icon icon-menu\"></div></div><div class=\"NavbarContainer-left\"><div class=\"Navbar-logo-container\"><a aria-current=\"page\" class=\"Navbar-logo active\" href=\"/\"><svg width=\"94\" height=\"34\" viewBox=\"0 0 94 34\" class=\"icon stroke fill\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"><title>Imgur</title><path d=\"M86.8012 17.336C86.8012 15.6108 86.981 15.0039 88.5637 14.3332C89.1292 14.0977 89.7077 13.9104 90.2587 13.7321C91.9506 13.1844 93.3833 12.7206 93.3833 11.2023C93.3833 9.86081 92.0884 8.71123 90.5059 8.71123C89.175 8.71123 87.8441 9.28632 86.6572 10.4038C85.9738 9.31751 85.0747 8.80659 83.9599 8.80659C82.0176 8.80659 81.1185 9.98884 81.1185 12.4487V22.5745C81.1185 25.0344 82.0176 26.2484 83.9599 26.2484C85.9018 26.2484 86.8012 25.0344 86.8012 22.5745V17.336Z\" fill=\"#ffffff\"></path><path d=\"M61.085 19.1569C61.085 23.9801 64.1422 26.5359 69.6448 26.5359C75.148 26.5359 78.2051 23.9801 78.2051 19.1569V12.4487C78.2051 9.98884 77.342 8.80659 75.3995 8.80659C73.4576 8.80659 72.5582 9.98884 72.5582 12.4487V18.1345C72.5582 20.4984 71.9469 21.8081 69.6448 21.8081C67.3433 21.8081 66.7314 20.4984 66.7314 18.1345V12.4487C66.7314 9.98884 65.8326 8.80659 63.9264 8.80659C61.9841 8.80659 61.085 9.98884 61.085 12.4487V19.1569Z\" fill=\"#ffffff\"></path><path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M48.245 33.3078C52.2732 33.3078 55.2229 31.9981 56.877 29.5382C57.9919 27.9092 58.2077 25.6094 58.2077 22.5745V12.4487C58.2077 9.98884 57.3086 8.80659 55.3663 8.80659C54.5036 8.80659 53.4964 9.34959 52.8485 10.4038C51.6982 9.19008 50.2589 8.61499 48.317 8.61499C43.4974 8.61499 39.5052 12.4166 39.5052 17.5276C39.5052 22.6387 43.2456 26.2802 47.7057 26.2802C49.9351 26.2802 51.6262 25.4814 52.8485 23.9163C52.8485 24.0322 52.8582 24.1398 52.8675 24.2432C52.8762 24.339 52.8845 24.4313 52.8845 24.5234C52.8845 27.2705 51.2304 28.7718 48.4607 28.7718C46.6897 28.7718 45.3742 28.2995 44.3043 27.9153C43.5708 27.652 42.9527 27.43 42.3822 27.43C40.9796 27.43 39.9367 28.3565 39.9367 29.6342C39.9367 31.6152 43.0655 33.3078 48.245 33.3078ZM45.3676 17.5276C45.3676 15.0039 46.9864 13.4067 49.0726 13.4067C51.1583 13.4067 52.8125 15.0039 52.8125 17.5276C52.8125 20.0829 51.1944 21.7442 49.0726 21.7442C46.9506 21.7442 45.3676 20.1149 45.3676 17.5276Z\" fill=\"#ffffff\"></path><path d=\"M31.5924 22.5745C31.5924 25.0344 32.4558 26.2484 34.3975 26.2484C36.34 26.2484 37.2388 25.0344 37.2388 22.5745V15.3553C37.2388 11.011 34.7573 8.74302 30.8371 8.74302C28.4996 8.74302 26.8446 9.41375 25.1184 11.1069C23.7877 9.54178 21.9172 8.74302 19.4717 8.74302C17.5655 8.74302 16.1268 9.25424 14.9396 10.4038C14.2563 9.31751 13.3575 8.80659 12.2426 8.80659C10.3 8.80659 9.40124 9.98884 9.40124 12.4487V22.5745C9.40124 25.0344 10.3 26.2484 12.2426 26.2484C14.1842 26.2484 15.0836 25.0344 15.0836 22.5745V16.8251C15.0836 14.3332 15.8753 13.0556 17.925 13.0556C19.7595 13.0556 20.4788 14.3332 20.4788 16.8887V22.5745C20.4788 25.0344 21.3776 26.2484 23.3202 26.2484C25.2624 26.2484 26.1615 25.0344 26.1615 22.5745V16.8251C26.1615 14.3332 26.9526 13.0556 29.0026 13.0556C30.8371 13.0556 31.5924 14.3332 31.5924 16.8887V22.5745Z\" fill=\"#ffffff\"></path><path d=\"M6.23549 12.4487C6.23549 9.98884 5.33669 8.80659 3.43046 8.80659C1.48851 8.80659 0.589111 9.98884 0.589111 12.4487V22.5745C0.589111 25.0344 1.48851 26.2484 3.43046 26.2484C5.373 26.2484 6.23549 25.0344 6.23549 22.5745V12.4487Z\" fill=\"#ffffff\"></path><path d=\"M3.51952 0.756104C1.58599 0.756104 0 2.1078 0 3.75626C0 5.43752 1.54695 6.7561 3.51952 6.7561C5.45305 6.7561 7 5.43752 7 3.75626C7 2.1078 5.45305 0.756104 3.51952 0.756104Z\" fill=\"#1BB76E\"></path></svg></a></div><a class=\"Button upload\" href=\"/upload?beta\"><img src=\"https://s.imgur.com/desktop-assets/desktop-assets/icon-new-post.13ab64f9f36ad8f25ae3544b350e2ae1.svg\">New post</a><a href=\"https://imgur.com\" class=\"GoUp-stopDrag\"><img class=\"GoUp GoUp-image GoUp-stopDrag ProfileCover-up\" src=\"//s.imgur.com/images/favicon-32x32.png\"></a></div><div class=\"NavbarContainer-center\"><div class=\"Searchbar\"><form class=\"Searchbar-form\"><input placeholder=\"Images, #tags, @users oh my!\" type=\"text\" class=\"Searchbar-textInput\" value=\"\" style=\"height: 36px;\"><button type=\"submit\" class=\"Searchbar-submitInput\"><img class=\"search\" src=\"https://s.imgur.com/desktop-assets/desktop-assets/icon-search.8d0f9b564a4659d48d8eca38b968a7f2.svg\"></button></form></div></div><div class=\"NavbarContainer-right\"><div class=\"ProfileNavbar\"><div class=\"NavbarNotifications NavbarNotifications-navbar\"><div class=\"ProfileNavbar-item uScaleTransition\"><a title=\"Top Comments\" href=\"/leaderboard\"><img class=\"leaderboard uScaleTransition ProfileNavbar-item leaderboard\" src=\"https://s.imgur.com/desktop-assets/desktop-assets/icon-leaderboard.2c7c197ab7cc58a23c14b83dcc3025a9.svg\"></a></div></div><a class=\"Navbar-signin\" href=\"https://imgur.com/signin?redirect=".$path."\">Sign in</a><a class=\"ButtonLink Button Navbar-signup\" title=\"Sign up\" href=\"https://imgur.com/register?redirect=".$path."\"><span class=\"Button-label\">Sign up</span></a></div></div></div><div class=\"Cover-metadata\"><h1 class=\"Cover-name\">".substr($path,3)."</h1></div><div class=\"CoverChangeGallery\" style=\"width: 496px; bottom: -29px;\"><a href=\"https://imgur.com\" class=\"GoUp-stopDrag\"><img class=\"GoUp GoUp-image GoUp-stopDrag\" src=\"//s.imgur.com/images/favicon-32x32.png\"></a><div class=\"NewCover-change-sort\"><div class=\"LayoutStub\"></div><span class=\"NewCover-change-sort-wrapper\"><div class=\"Dropdown sort Dropdown--upper\"><div class=\"Dropdown-title\"><span>Popular</span><span class=\"Dropdown-icon icon-dropdown-arrow\"></span></div><div class=\"Dropdown-menu Dropdown-menu--bottom\"><div class=\"Dropdown-triangle\"></div><div class=\"Dropdown-list\"><div class=\"Dropdown-option isActive\">Popular</div><div class=\"Dropdown-option\">Newest</div><div class=\"Dropdown-option\">Best</div></div></div></div></span></div><div class=\"CoverChangedView\"><div class=\"Tooltip-dataTitle pause active\" data-title=\"Disable Autoplay\"><img class=\"pause\" src=\"https://s.imgur.com/desktop-assets/desktop-assets/icon-pause.68f07ce1a7e07bac06d1f2c527d7a9e5.svg\"></div><div class=\"Tooltip-dataTitle waterfall active\" data-title=\"Waterfall\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"17\" height=\"17\"><rect id=\"backgroundrect\" width=\"100%\" height=\"100%\" x=\"0\" y=\"0\" fill=\"none\" stroke=\"none\"></rect><g><g><g id=\"svg_10\"><polygon points=\"6.178707122802734,0 1.1573245525360107,0 0.15306150913238525,0 0.15306150913238525,1.2756938815360286 0.15306150913238525,7.654227611753868 0.15306150913238525,8.929917633937634 1.1573245525360107,8.929917633937634 6.178707122802734,8.929917633937634 7.182987213134766,8.929917633937634 7.182987213134766,7.654227611753868 7.182987213134766,1.2756938815360286 7.182987213134766,0 \"></polygon></g></g><g><g id=\"svg_34\"><polygon points=\"6.278707504272461,12.099999867934457 1.25732421875,12.099999867934457 0.25306129455566406,12.099999867934457 0.25306129455566406,12.804268837391646 0.25306129455566406,16.325651648036 0.25306129455566406,17.029918670654297 1.25732421875,17.029918670654297 6.278707504272461,17.029918670654297 7.282987594604492,17.029918670654297 7.282987594604492,16.325651648036 7.282987594604492,12.804268837391646 7.282987594604492,12.099999867934457 \"></polygon></g></g><g><g id=\"svg_38\"><polygon points=\"15.978708267211914,8.100003717719119 10.957324981689453,8.100003717719119 9.953062057495117,8.100003717719119 9.953062057495117,9.361411389385012 9.953062057495117,15.66851682793822 9.953062057495117,16.929922103881836 10.957324981689453,16.929922103881836 15.978708267211914,16.929922103881836 16.982988357543945,16.929922103881836 16.982988357543945,15.66851682793822 16.982988357543945,9.361411389385012 16.982988357543945,8.100003717719119 \"></polygon></g></g><g><g><polygon points=\"15.878707885742188,-0.10000000149011612 10.857324600219727,-0.10000000149011612 9.85306167602539,-0.10000000149011612 9.85306167602539,0.6185544841542594 9.85306167602539,4.2113652655988005 9.85306167602539,4.929918346846392 10.857324600219727,4.929918346846392 15.878707885742188,4.929918346846392 16.88298797607422,4.929918346846392 16.88298797607422,4.2113652655988005 16.88298797607422,0.6185544841542594 16.88298797607422,-0.10000000149011612 \"></polygon></g></g></g></svg></div><div class=\"Tooltip-dataTitle uniform\" data-title=\"Uniform\"><svg xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\" width=\"17\" height=\"17\"><rect id=\"backgroundrect\" width=\"100%\" height=\"100%\" x=\"0\" y=\"0\" fill=\"none\" stroke=\"none\"></rect><g><g><g><polygon points=\"6.17870715566179,0 1.1573245256338325,0 0.15306144952774048,0 0.15306144952774048,1.0042672515679527 0.15306144952774048,6.0256540079346905 0.15306144952774048,7.02991762439467 1.1573245256338325,7.02991762439467 6.17870715566179,7.02991762439467 7.18298727747689,7.02991762439467 7.18298727747689,6.0256540079346905 7.18298727747689,1.0042672515679527 7.18298727747689,0 \"></polygon></g></g><g><g><polygon points=\"6.278707504272461,10 1.25732421875,10 0.25306129455566406,10 0.25306129455566406,11.004266738891602 0.25306129455566406,16.025653839111328 0.25306129455566406,17.029918670654297 1.25732421875,17.029918670654297 6.278707504272461,17.029918670654297 7.282987594604492,17.029918670654297 7.282987594604492,16.025653839111328 7.282987594604492,11.004266738891602 7.282987594604492,10 \"></polygon></g></g><g><g><polygon points=\"15.978708267211914,9.900003053247929 10.957324981689453,9.900003053247929 9.953062057495117,9.900003053247929 9.953062057495117,10.90426979213953 9.953062057495117,15.925656892359257 9.953062057495117,16.929921723902225 10.957324981689453,16.929921723902225 15.978708267211914,16.929921723902225 16.982988357543945,16.929921723902225 16.982988357543945,15.925656892359257 16.982988357543945,10.90426979213953 16.982988357543945,9.900003053247929 \"></polygon></g></g><g><g><polygon points=\"15.878707885742188,-0.10000000149011612 10.857324600219727,-0.10000000149011612 9.85306167602539,-0.10000000149011612 9.85306167602539,0.9042667374014854 9.85306167602539,5.925653837621212 9.85306167602539,6.929918669164181 10.857324600219727,6.929918669164181 15.878707885742188,6.929918669164181 16.88298797607422,6.929918669164181 16.88298797607422,5.925653837621212 16.88298797607422,0.9042667374014854 16.88298797607422,-0.10000000149011612 \"></polygon></g></g></g></svg></div></div></div>";
$insert.="<link rel=\"stylesheet\" href=\"https://s.imgur.com/desktop-assets/css/22.styles.29fd1b246a84dc4fc1c8.css\">
<link rel=\"stylesheet\" href=\"https://s.imgur.com/desktop-assets/css/22.styles.29fd1b246a84dc4fc1c8.css\">
<link rel=\"stylesheet\" href=\"https://s.imgur.com/desktop-assets/css/1.styles.ce4eaef1c4aea2615c47.css\">
</div>";
$insert.="<div class=\"Spinner-contentWrapper\"><div class=\"FastGrid\" style=\"margin-top: 411px; width: 1008px;\"><table style=\"width:1008px;\"><tr><td style=\"border:16px;vertical-align:top;\">";
$p[0]="<div class=\"Grid-Column\" style=\"width: 240px;\">";
$p[1]="</td><td style=\"border:16px;vertical-align:top;\"><div class=\"Grid-Column hasMargin\" style=\"width: 240px;\">";
//left:344px;top:411px;position:absolute;\">";
$p[2]="</td><td style=\"border:16px;vertical-align:top;\"><div class=\"Grid-Column hasMargin\" style=\"width: 240px;\">";
$p[3]="</td><td style=\"border:16px;vertical-align:top;\"><div class=\"Grid-Column hasMargin\" style=\"width: 240px;\">";
//loop
$top0="";$top1="";$top2="";$top3="";
$ex=explode("},{\"id",$e);
$z=count($ex);
for($i=0;$i<$z;$i++) {
//if ($i==1) {$ex[$i]=$ex[0].$ex[1];}
{

if (strpos($ex[$i],"e,\"link\":")>0) {
$link=substr($ex[$i],strpos($ex[$i],"e,\"link\":")+10);
$link=substr($link,0,strpos($link,"\",\""));
}
else {
$link=substr($ex[$i],strpos($ex[$i],",\"link\":")+9);
$link=substr($link,0,strpos($link,"\","));
}
$link=str_replace("\\","",$link);
if (strpos($ex[$i],"\",\"animated\":true,\"")>0) {
if (strpos($ex[$i],"h.gif")>0) {
$link=str_replace("h.gif","_d.jpg",$link);}
else {$link=str_replace(".gif","_d.jpg",$link);}
if (strpos($ex[$i],"/i.")===false) {$link=str_replace("/imgur.","/i.imgur.",$link);}
}


$title=substr($ex[$i],strpos($ex[$i],",\"title\":")+9);
$title=substr($title,0,strpos($title,",\""));
if ($title==="null") {$title="";continue;}
else {
$title=substr($title,1);
$title=substr($title,0,strlen($title)-1);
}

$title=str_replace("\u2019","'",$title);
$title=str_replace("\u0026","&",$title);

$ic=1;
if (strpos($ex[$i],",\"images_count\":")>0) {
$ic=substr($ex[$i],strpos($ex[$i],",\"images_count\":")+16);
$ic=substr($ic,0,strpos($ic,",\""));}


$views=substr($ex[$i],strpos($ex[$i],",\"views\":")+9);
$views=substr($views,0,strpos($views,",\""));

$up=substr($ex[$i],strpos($ex[$i],",\"ups\":")+7);
$up=substr($up,0,strpos($up,",\""));

$downs=substr($ex[$i],strpos($ex[$i],",\"downs\":")+9);
$downs=substr($downs,0,strpos($downs,",\""));
if (!is_numeric($ups)) {
$ex[$i].=$ex[$i+1];
$up=substr($ex[$i],strpos($ex[$i],",\"ups\":")+7);
$up=substr($up,0,strpos($up,",\""));
}
if (!is_numeric($downs)) {
$ex[$i].=$ex[$i+1];
$downs=substr($ex[$i],strpos($ex[$i],",\"downs\":")+9);
$downs=substr($downs,0,strpos($downs,",\""));

}



$cmt=substr($ex[$i],strpos($ex[$i],",\"comment_count\":")+17);
$cmt=substr($cmt,0,strpos($cmt,",\""));

$w=240;$h=240;
if (strpos($ex[$i],"\",\"animated\":true,\"")>0) {
$w=substr($ex[$i],strpos($ex[$i],",\"width\":")+9);
$w=substr($w,0,strpos($w,",\""));
$h=substr($ex[$i],strpos($ex[$i],",\"height\":")+10);
$h=substr($h,0,strpos($h,",\""));

} else {
$w=substr($ex[$i],strpos($ex[$i],",\"cover_width\":")+15);
$w=substr($w,0,strpos($w,",\""));
$h=substr($ex[$i],strpos($ex[$i],",\"cover_height\":")+16);
$h=substr($h,0,strpos($h,",\""));
}
if (!is_numeric($w)) {$w=240;

//$ex[$i].=$ex[$i+1];
//$w=substr($ex[$i],strpos($ex[$i],",\"cover_width\":")+15);
//$w=substr($w,0,strpos($w,",\""));
}
if (!is_numeric($h)) {$h=240;
//$ex[$i].=$ex[$i+1];
//$h=substr($ex[$i],strpos($ex[$i],",\"cover_height\":")+16);
//$h=substr($h,0,strpos($h,",\""));

}

$scale=240/$w;
$h=$scale*$h;
$h2=$h+67;
$jump=30;
$left=0;
$top=0;
if ($h2>495-67) {$h2=495-67;}

$ih3=$h2;
//if (strlen($text)>70) {$jump+=16;}
if ($top0<=$top1 && $top0<=$top2 && $top0<=$top3) {$left=0;$top=$top0;$top0+=$ih3+$jump;}
else if ($top1<=$top2 && $top1<=$top3) {$left=250;$top=$top1;$top1+=$ih3+$jump;}
else if ($top2<=$top3) {$left=500;$top=$top2;$top2+=$ih3+$jump;}
else {$left=750;$top=$top3;$top3+=$ih3+$jump;}
$index=$left/250;
$p[$index].="<div style=\"height: ".$h2."px; margin-bottom: 16px;\">
<a draggable=\"false\" href=\"".$link."\" class=\"Post-item novote\" target=\"_self\">
<div class=\"Post-item-media\" style=\"height: ".$h."px;\">
<img src=\"".$link."?maxwidth=520&amp;shape=thumb&amp;fidelity=high\" style=\"width: 240px; height: ".$h."px;\"></div>
<div class=\"Post-item-meta\">";
if ($ic>1){
$p[$index].="<div class=\"Post-item-image-count\">".$ic."</div>";
}
$p[$index].="</div>
<div class=\"Post-item-title-wrap\">
<div class=\"Post-item-title\">
<span style=\"display: -webkit-box; max-height: 31.92px; overflow: hidden; text-overflow: ellipsis; -webkit-box-orient: vertical; -webkit-line-clamp: 2;\">".$title."</span></div>
<div class=\"Post-item-info\">
<div class=\"PostInfo vote ups uScaleTransition\">
<span class=\"icon icon-upvote-fill\"></span>
<span>".$up."</span>
</div>
<div class=\"PostInfo vote downs uScaleTransition\">
<span class=\"icon icon-downvote-fill\"></span>
<span>".$downs."</span>
</div>

<div class=\"PostInfo uScaleTransition\">
<span class=\"icon chat-filled\">
<img class=\"chat-filled\" src=\"https://s.imgur.com/desktop-assets/desktop-assets/icon-chat-filled.b12fed0067e4ce444f710411ee6a70e1.svg\"></span>
<span>".$cmt."</span></div>
<div class=\"PostInfo uScaleTransition views \">
<span class=\"icon eye\">
<img class=\"eye\" src=\"https://s.imgur.com/desktop-assets/desktop-assets/icon-eye.bf703aed21e47a30269da39879c03ccf.svg\"></span>
<span>".$views."</span></div></div></div></a></div>";

}}
$insert.=$p[0]."</div>".$p[1]."</div>".$p[2]."</div>".$p[3]."</div>";
//endloop
$insert.="</td></tr></table></div></div><div class=\"ButtonBackToTop\"><span class=\"text\">Take me up</span><span class=\"icon icon-take-me-up\"></span></div><div><div class=\"Footer Footer-slim\"><ul class=\"Footer-navbar\"><li>© 2020 Imgur, Inc</li><li><a href=\"https://imgur.com/about\">About</a></li><li><a href=\"https://imgurinc.com/press\">Press</a></li><li><a href=\"https://blog.imgur.com\">Blog</a></li><li><a href=\"https://imgur.com/privacy\">Privacy</a></li><li><a href=\"https://imgur.com/ccpa\">CCPA</a></li><li><a href=\"https://imgur.com/tos\">Terms</a></li></ul><div class=\"Dropdown center Dropdown-col-2\"><div class=\"Dropdown-title\"><span> </span><img src=\"https://s.imgur.com/desktop-assets/desktop-assets/icon-points.5867bfb88971853dcfdf49b18f8455b9.svg\" class=\"Dropdown-icon icon-points\"></div><div class=\"Dropdown-menu Dropdown-menu--bottom\"><div class=\"Dropdown-triangle\"></div><div class=\"Dropdown-list\"><div class=\"Dropdown-option\"><a href=\"https://imgurinc.com/advertise\">Advertise</a></div><div class=\"Dropdown-option\"><a href=\"https://imgur.com/privacy#adchoices\">Ad Choices</a></div><div class=\"Dropdown-option\"><a href=\"https://imgur.com/rules\">Rules</a></div><div class=\"Dropdown-option\"><a href=\"https://help.imgur.com/hc/en-us\">Help</a></div><div class=\"Dropdown-option\"><a href=\"https://imgurinc.com/careers\">Careers</a></div><div class=\"Dropdown-option\"><a href=\"http://store.imgur.com\">Store</a></div><div class=\"Dropdown-option\"><a href=\"https://apidocs.imgur.com\">API</a></div></div></div></div><div class=\"Footer-slim-app\"><a href=\"https://imgur.com/apps\">Get the App</a></div></div><div class=\"Footer Footer-slim Footer-measure\"><ul class=\"Footer-navbar Footer-navbar-dimensions\"><li>© 2020 Imgur, Inc</li><li><a href=\"https://imgur.com/about\">About</a></li><li><a href=\"https://imgurinc.com/press\">Press</a></li><li><a href=\"https://blog.imgur.com\">Blog</a></li><li><a href=\"https://imgur.com/privacy\">Privacy</a></li><li><a href=\"https://imgur.com/ccpa\">CCPA</a></li><li><a href=\"https://imgur.com/tos\">Terms</a></li><li><a href=\"https://imgurinc.com/advertise\">Advertise</a></li><li><a href=\"https://imgur.com/privacy#adchoices\">Ad Choices</a></li><li><a href=\"https://imgur.com/rules\">Rules</a></li><li><a href=\"https://help.imgur.com/hc/en-us\">Help</a></li><li><a href=\"https://imgurinc.com/careers\">Careers</a></li><li><a href=\"http://store.imgur.com\">Store</a></li><li><a href=\"https://apidocs.imgur.com\">API</a></li></ul></div></div>";
$insert.="<link rel=\"stylesheet\" href=\"https://s.imgur.com/desktop-assets/css/22.styles.263d22f69e63bb355c3f.css\">";
$insert.="<link rel=\"stylesheet\" href=\"https://s.imgur.com/desktop-assets/css/3.styles.5f821d770dee8ae0cf5d.css\">";

$insert.="<link rel=\"stylesheet\" href=\"https://s.imgur.com/desktop-assets/css/22.styles.29fd1b246a84dc4fc1c8.css\">";
$insert.="<link rel=\"stylesheet\" href=\"https://s.imgur.com/desktop-assets/css/styles.c4bb87a94f3ff0fce97a.css\">";

$r=$pre.$insert.$post;
return $r;
}
else {

$ex=explode("=\"post-image-container\" ",$c);
$d=count($ex);
$r=$ex[0];
for($i=1;$i<$d;$i++) {
$lastid=substr($ex[$i-1],strrpos($ex[$i-1]," id=")+4);
$lastid=substr($lastid,1,strpos($lastid," ")-1);
$pre=substr($ex[$i],0,strpos($ex[$i],"</div"));
$insert="<a class=\"zoom\" title=\"\" href=\"//i.imgur.com/".$lastid.".png\"><img alt=\"\" src=\"//i.imgur.com/".$lastid.".png\" class=\"post-image-placeholder\" style=\"max-width: 100%; min-height: 410px;\" original-title=\"\"><img alt=\"\" src=\"//i.imgur.com/".$lastid.".png\" style=\"max-width: 100%; min-height: 410px;\"></a>";
$post=substr($ex[$i],strpos($ex[$i],"</div"));
$r.=$pre.$insert.$post;
}
return $r;
}}
else{
//"imgur.com

$str=substr($b,strpos($b,".com")+4);
if (substr($str,0,1)==="/") {$str=substr($str,1);}
if (substr($str,strlen($str)-1,1)==="/") {$str=substr($str,0,strlen($str)-1);}

if (substr_count($str,"/")==0) {
$e=templatehelp("https://imgur.com/gallery/hot/viral/page/0/hit.json");
$ex=explode("post-image-container p",$c);
$d=count($ex);
$r=$ex[0];
for($i=1;$i<$d;$i++) {
$lastid=substr($ex[$i-1],strrpos($ex[$i-1]," id=")+4);
$lastid=substr($lastid,1,strpos($lastid," ")-1);
$pre=substr($ex[$i],0,strpos($ex[$i],"</div"));
$insert="<a class=\"zoom\" title=\"\" href=\"//i.imgur.com/".$lastid.".png\"><img alt=\"\" src=\"//i.imgur.com/".$lastid.".png\" class=\"post-image-placeholder\" style=\"max-width: 100%; min-height: 410px;\" original-title=\"\"><img alt=\"\" src=\"//i.imgur.com/".$lastid.".png\" style=\"max-width: 100%; min-height: 410px;\"></a>";
$post=substr($ex[$i],strpos($ex[$i],"</div"));
$r.=$pre.$insert.$post;

}//endfor
//return $r;
$c=$r;
$r="";
$pre=substr($c,0,strpos($c,"<div id=\"side-gallery"));
//echo $pre;
//exit;
$post=substr($c,strlen($pre));
//return $pre.$post;
$post=substr($post,strpos($post,"<div class=\"advertisement"));
//echo $pre.$post;
//exit;
$insert="</div></div></div></div></div><div id=\"right-content\" class=\"right post-unification\"><div id=\"post-options\"></div><div id=\"side-gallery\"><div data-reactroot=\"\"><div class=\"sg-header\"><a class=\"sg-title-container\" href=\"/gallery\"><h2 class=\"sg-title\">Most Viral Images</h2><h3 class=\"sg-subtitle\">sorted by popularity</h3></a><div class=\"layout-options\"><a class=\"layout-button selected\"><span class=\"icon-right-list\"></span></a><a class=\"layout-button\"><span class=\"icon-grid\"></span></a></div></div><div class=\"nano has-scrollbar\"><div class=\"content scroll-content cf\" tabindex=\"0\" style=\"right: -17px; overflow-y: scroll;\"><div class=\"items list\">";
//loop
$ex=explode("}},{\"id\":",$e);
$z=count($ex);
for($i=0;$i<$z;$i++) {

//$title=substr($ex[$i],strpos($ex[$i],"\"title\":")+9);
//$title=substr($title,0,strpos($title,"\","));
$title=substr($ex[$i],strpos($ex[$i],",\"title\":")+9);
$title=substr($title,0,strpos($title,",\""));
if ($title==="null") {$title="";continue;}
else {
$title=substr($title,1);
$title=substr($title,0,strlen($title)-1);
}

$title=str_replace("\u2019","'",$title);
$title=str_replace("\u0026","&",$title);

$gall=substr($ex[$i],strpos($ex[$i],"\"hash\":")+8);
$gall=substr($gall,0,strpos($gall,"\","));

if (strpos($ex[$i],"\"album_cover\":")>0) {
$al=substr($ex[$i],strpos($ex[$i],"\"album_cover\":")+15);
$al=substr($al,0,strpos($al,"\","));
} else {continue;}
if (substr($al,0,4)==="ull.") {$al=$gall;}
if (substr($al,0,4)==="ull,") {$al=$gall;}
$insert.="<a class=\"sg-item base list\" href=\"/gallery/".$gall."\">
<div class=\"sg-list-image\" style=\"background-image: url(&quot;//i.imgur.com/".$al."b.jpg&quot;);\"></div>
<div class=\"sg-list-metadata\">
<div class=\"sg-list-title\">".$title."</div></div></a>";


}

//endloop
$insert.="</div><div class=\"pane\"><div class=\"slider\" style=\"height: 46px; top: 0px;\"></div></div></div><div class=\"fade-out\"></div></div>";
$post=str_replace("/gallery.js","/",$post);
//$post=substr($post,0,strpos($post," src=\"//s.imgur.com/min/px.js"))."></script></body></html>";

$prepre=substr($pre,0,strpos($pre,"post-image-container")+21);

$postpre=substr($pre,strlen($prepre));
$postpre=substr($postpre,strpos($prepre,"</div")+6);


$pid=substr($prepre,strrpos($prepre,"id=")+4);
$pid=substr($pid,0,strpos($pid,"\""));

$prepre.="><div class=\"image post-image\"><a class=\"zoom\" title=\"\" href=\"//i.imgur.com/".$pid.".jpg\"><img alt=\"\" src=\"//i.imgur.com/".$pid.".jpg\" class=\"post-image-placeholder\" style=\"max-width: 100%; min-height: 970.667px;\" original-title=\"\"><img alt=\"\" src=\"//i.imgur.com/".$pid."g.jpg\" style=\"max-width: 100%; min-height: 970.667px;\"></a></div>";

$desc=substr($pre,strpos($pre,"og:desc"));
$desc=substr($desc,strpos($desc,"=")+2);
$desc=substr($desc,0,strpos($desc,"\""));
$views=substr($post,strpos($post,"\"views\":")+9);
$views=substr($views,0,strpos($views,"\","));


$prepre.="<div class=\"post-image-meta\"><div><div class=\"post-image-description\">".$desc."</div><span></span></div></div>";

$prepre.="<div class=\"post-action\"><div><div class=\"post-action-actions post-action-single\">
<button class=\"post-action-icon post-action-favorite icon-favorite-outline\" style=\"transform: scale(1);\"></button>
<a class=\"Open-Save-To-Folder-Button\">+</a>
<span class=\"post-action-stats\">
<span class=\"\">".$views." views</span></span>
<div class=\"post-action-options pointer right\">
<span class=\"icon-ellipses\"></span>
<ul class=\"post-action-options-items\">
<li><a class=\"post-action-option\" href=\"#\">
<span class=\"icon-download post-action-options-items-icon\"></span>
<span class=\"post-action-link-text\">Download Post</span></a></li><li>
<a class=\"post-action-option\" href=\"#\">
<span class=\"icon-embed post-action-options-items-icon\"></span>
<span class=\"post-action-link-text\">Embed Post</span></a></li>
<li><a class=\"post-action-option\" href=\"/memegen/create/".$pid."\">
<span class=\"icon-meme post-action-options-items-icon\"></span>
<span class=\"post-action-link-text\">Make Meme</span></a></li></ul></div>
<div class=\"right\"><span class=\"social-icon icon-f\"></span>
<span class=\"social-icon icon-twttr\"></span>
<span class=\"social-icon icon-pin\"></span>
<span class=\"social-icon icon-reddit\"></span></div></div>
<div class=\"post-action-tags\"></div></div></div>";

$pre=$prepre.$postpre;

$prepre=substr($pre,0,strpos($pre,"<div class=\"left post-pad\">"));
$postpre=substr($pre,strlen($prepre));
$gap=substr($postpre,0,strpos($postpre,"<div id=\"".$pid));
$postpre=substr($postpre,strpos($postpre,"<div id=\"".$pid));

$prepre.="<div class=\"left post-pad\">
<div id=\"promoted-spotlight\"></div>
<div id=\"div-ad-top_banner\" class=\"advertisement nodisplay div-ad-active-top_banner\"></div>
<div class=\"post-container\" style=\"padding-top: 0px;\">
<div class=\"post-header\">
<div data-reactroot=\"\">
<div class=\"next-prev\">
<div class=\"btn navPrev icon icon-arrow-left disabled\"></div>
<div class=\"btn btn-action navNext\">
<div class=\"text\">Next Post</div>
<span class=\"icon icon-arrow-right\"></span></div></div>
<div class=\"post-title-container\"></div>
<div class=\"post-title-meta font-opensans-semibold post-title-meta--notitle\"><span>";
$gap=substr($gap,strpos($gap,"Uploaded"));
$gap=substr($gap,0,strpos($gap,"</span>"));
$prepre.=$gap."</span></div></div></div><div class=\"post-images\"><div data-reactroot=\"\">";

$pre=$prepre.$postpre;



$r=$pre.$insert.$post;
//$r=str_replace("padding-top: 82px;","padding-top: 0px;",$r);
return $r;
}
else{

$ex=explode("post-image-container p",$c);
$d=count($ex);
$r=$ex[0];
for($i=1;$i<$d;$i++) {
$lastid=substr($ex[$i-1],strrpos($ex[$i-1]," id=")+4);
$lastid=substr($lastid,1,strpos($lastid," ")-1);
$pre=substr($ex[$i],0,strpos($ex[$i],"</div"));
$insert="<a class=\"zoom\" title=\"\" href=\"//i.imgur.com/".$lastid.".png\"><img alt=\"\" src=\"//i.imgur.com/".$lastid.".png\" class=\"post-image-placeholder\" style=\"max-width: 100%; min-height: 410px;\" original-title=\"\"><img alt=\"\" src=\"//i.imgur.com/".$lastid.".png\" style=\"max-width: 100%; min-height: 410px;\"></a>";
$post=substr($ex[$i],strpos($ex[$i],"</div"));
$r.=$pre.$insert.$post;
}}}

}//endelse {imgur
else if ($a==="reddit.com"){
$c=str_replace("https://www.redditstatic.com/desktop2x/ChatPost","https://0.0.0.0/www.redditstatic.com/desktop2x/ChatPost",$c);
$ex=explode("px\" tabindex=\"-1",$c);
$d=count($ex);
$r=$ex[0];
$mdata=substr($ex[$d-1],strpos($ex[$d-1],"__perfMark('redux_json_start')"));
for($i=1;$i<$d;$i++)
{
$pre=substr($ex[$i],0,strpos($ex[$i],"</button"));
$post=substr($ex[$i],strpos($ex[$i],"</button"));
$r.="px\" tabindex=\"-1".$pre;
$pre=substr($post,0,strpos($post,"<button"));
$post=substr($post,strpos($post,"<button"));
$r.=$pre;
$pre=substr($post,0,strpos($post,"</button "));
$post=substr($post,strpos($post,"</button "));
$r.=$pre;
$pre=substr($post,0,strpos($post,"</span"));
$post=substr($post,strpos($post,"</span"));
$r.=$pre;
$pre=substr($post,0,strpos($post,"<span "));
$post=substr($post,strpos($post,"<span "));
$r.=$pre;
$pre=substr($post,0,strpos($post,"</span"));
$post=substr($post,strpos($post,"</span"));
$r.=$pre;
$pre=substr($post,0,strpos($post,"<span "));
$post=substr($post,strpos($post,"<span "));
$data=substr($post,strpos($post,"<p "));
$data=substr($data,strpos($data,">")+1);
$data="\"".substr($data,0,strpos($data,"</p"))."\"";
if (strpos($data,"\"Continue this thread</span>")!==FALSE) {$r.=$pre.$post;}
 else {
$chop=substr($mdata,0,strpos($mdata,$data));
//echo $chop."<br>".$data."<br>".$post."<br>".$ex[$i];
$au=substr($chop,strrpos($chop,"{\"author\":\"")+11);
//echo "<br>".$au;
$au=substr($au,0,strpos($au,"\",\""));
//echo "<br>".$au;exit;
$id=substr($chop,strrpos($chop,",\"id\":\"")+7);
$id=substr($id,0,strpos($id,"\",\""));
$r.=$pre."<div class=\"_2mHuuvyV9doV3zwbZPtIPG\"><a class=\"f3THgbzMYccGW8vbqZBUH _23wugcdiaj44hdfugIAlnX \" href=\"/user/".$au."\">".$au."</a><div class=\"_16AAktgl_rVbXAeiWU9CQd\" id=\"UserInfoTooltip--".$id."\"></div></div>".$post;
}//endelse
}//endfor
}//endelse reddit
else if ($a==="instagram.com" && $b=="/p/") {
$c=str_replace("\u2019","'",$c);
$c=str_replace("\u0026","&",$c);
$c=str_replace("background: white;","",$c);
$head=substr($c,0,strpos($c,"<head"));
$c=substr($c,strpos($c,"<head"));
$c=$head."<base href=\"https://www.instagram.com\">".$c;
$pre=substr($c,0,strpos($c,"window._sharedData ="));
$json=substr($c,strlen($pre));
$json=substr($json,0,strpos($json,"}}</script>")+2);
$post=substr($c,strlen($pre.$json));
$pre=substr($pre,0,strpos($pre,"\"react-root\"")+13);
$pid=substr($json,strpos($json,",\"id\":")+7);
$pid=substr($pid,0,strpos($pid,",")-1);
$fn=substr($json,strpos($json,",\"full_name\":")+14);
$fn=substr($fn,0,strpos($fn,"\",\""));
$bio=substr($json,strpos($json,"{\"biography\":")+14);
$eurl=substr($json,strpos($json,",\"external_url_linkshimmed\":")+29);
$eurl=substr($eurl,0,strpos($eurl,"\",\""));
$link=substr($json,strpos($json,",\"external_url\":")+17);
$link=substr($link,0,strpos($link,"\",\"")-1);
$bio=substr($bio,0,strpos($bio,"\",\"")-1);
$pp=substr($json,strrpos($json,",\"profile_pic_url\":")+20);
$pp=substr($pp,0,strpos($pp,"\",\""));
$iv=substr($json,strpos($json,",\"is_verified\":")+15);
$iv=substr($iv,0,strpos($iv,","));
$rep=substr($json,strpos($json,",\"edge_owner_to_timeline_media\":")+30);
$rep=substr($rep,strpos($rep,":{\"count\":")+10);
$rep=substr($rep,0,strpos($rep,","));
$rep4=$rep;
if ($rep>999) {$l=strlen($rep);$rep=substr($rep,0,$l-3).",".substr($rep,$l-3);$rep4=$rep;}
$rgc=substr($json,strpos($json,",\"edge_followed_by\":")+21);
$rgc=substr($rgc,strpos($rgc,":{\"count\":")+10);
$rgc=substr($rgc,0,strpos($rgc,",")-1);
$rgc2=$rgc;$rgc3=$rgc;$rgc4=$rgc;
if ($rgc>999) {$rgc3=(int)($rgc/1000);$rgc3.="k";
$l=strlen($rgc);$rgc2=substr($rgc,0,$l-3).",".substr($rgc,$l-3);
$rgc4=$rgc2;
if ($rgc>9999) {$rgc4=$rgc3;}}
$fv=substr($json,strpos($json,",\"edge_followed\":")+18);
$fv=substr($fv,strpos($fv,":{\"count\":")+10);
$fv=substr($fv,0,strpos($fv,",")-1);
$fv2=$fv;$fv3=$fv;$fv4=$fv;
if ($fv>999) {$fv3=(int)($fv/1000);$fv3.="k";
$l=strlen($fv);$fv2=substr($fv,0,$l-3).",".substr($fv,$l-3);
$fv4=$fv2;
if ($fv>9999) {$fv4=$fv3;}}

$ac=substr($json,strpos($json,",\"accessibility_caption\":")+26);
$ac=substr($ac,0,strpos($ac,"}")-1);
$durl=substr($json,strpos($json,",\"display_url\":")+16);
$durl=substr($durl,0,strpos($durl,",")-1);
$tr=substr($json,strpos($json,",\"display_resources\":")+24);
$tr=substr($tr,0,strpos($tr,"]"));
$ex3=explode("{\"src\":\"",$tr);

$insert="<section class= \"_9eogI E3X2T \"><div></div><main class= \"SCxLW  o64aR  \" role= \"main \">
<div class= \"Kj7h1 \"><div class= \"ltEKP \">
<article class= \"QBXjJ M9sTE  L_LMM  JyscU  ePUX4 \">
<header class= \"Ppjfr UE9AK  wdOqh \">
<div class= \"RR-M- h5uC0 mrq0Z \" role= \"button \" tabindex= \"0 \">
<canvas class= \"CfWVH \" height= \"42 \" width= \"42 \" style= \"position: absolute; top: -5px; left: -5px; width: 42px; height: 42px; \"></canvas>
<span class= \"_2dbep  \" role= \"link \" tabindex= \"0 \" style= \"width: 32px; height: 32px; \">
<img alt= \"".$fn."'s profile picture \" class= \"_6q-tv \" src= \"".$pp."\"></span></div>
<div class= \"o-MQd  z8cbW \">
<div class= \"PQo_0 RqtMr \">
<div class= \"e1e1d \">
<h2 class= \"BrX75 \">
<a class= \"FPmhX notranslate  nJAzx \" title= \"".$fn." \" href= \"/".$fn."/ \">".$fn."</a></h2>
<span class= \"mewfM Szr5J  coreSpriteVerifiedBadgeSmall \" title= \"Verified \">Verified</span></div>
<div class= \"bY2yH \">
<span class= \"RPhNB \">•</span>";
$insert.="<a class= \"oW_lN \" rel= \"nofollow \" href= \"/accounts/login/?next=%2Fp%2FB79f17iBT3B%2F&amp;source=follow \"><button class= \"sqdOP yWX7d    y3zKF      \" type= \"button \">Follow</button></a></div></div><div class= \"M30cS \"><div></div><div class= \"JF9hh \"></div></div></div></header>";
$insert.="<div class= \"_97aPb wKWK0 \"><div role= \"button \" tabindex= \"0 \" class= \"ZyFrc \"><div class= \"eLAPa kPFhm \"><div class= \"KL4Bh \" style= \"padding-bottom: 100%; \"><img alt= \"".$ac."\" class= \"FFVAD \" decoding= \"auto \" sizes= \"598px \" srcset= ";
$y=count($ex3);
for($j=1;$j<$y;$j++) {
$timg2=substr($ex3[$j],0,strpos($ex3[$j],",")-1);
$tsize=substr($ex3[$j],strlen($timg2));
$tsize=substr($tsize,strpos($tsize,":"));
$tsize=substr($tsize,1,strpos($tsize,",")-1);
$tsize.="w";
$insert.=$timg2." ".$tsize.($j-1==$y?"\" ":",");
}
$insert.=" src=\"".$durl."\" style= \"object-fit: cover; \"></div>
<div class= \"_9AhH0 \"></div></div></div></div>
<div class= \"eo2As  \">
<section class= \"ltpMr Slqrh \">
<span class= \"fr66n \">
<button class= \"wpO6b  \" type= \"button \">
<svg aria-label= \"Like \" class= \"_8-yf5  \" fill= \"#262626 \" height= \"24 \" viewBox= \"0 0 48 48 \" width= \"24 \">
<path clip-rule= \"evenodd \" d= \"M34.3 3.5C27.2 3.5 24 8.8 24 8.8s-3.2-5.3-10.3-5.3C6.4 3.5.5 9.9.5 17.8s6.1 12.4 12.2 17.8c9.2 8.2 9.8 8.9 11.3 8.9s2.1-.7 11.3-8.9c6.2-5.5 12.2-10 12.2-17.8 0-7.9-5.9-14.3-13.2-14.3zm-1 29.8c-5.4 4.8-8.3 7.5-9.3 8.1-1-.7-4.6-3.9-9.3-8.1-5.5-4.9-11.2-9-11.2-15.6 0-6.2 4.6-11.3 10.2-11.3 4.1 0 6.3 2 7.9 4.2 3.6 5.1 1.2 5.1 4.8 0 1.6-2.2 3.8-4.2 7.9-4.2 5.6 0 10.2 5.1 10.2 11.3 0 6.7-5.7 10.8-11.2 15.6z \" fill-rule= \"evenodd \"></path></svg></button></span>
<span class= \"_15y0l \">
<button class= \"wpO6b  \" type= \"button \">
<svg aria-label= \"Comment \" class= \"_8-yf5  \" fill= \"#262626 \" height= \"24 \" viewBox= \"0 0 48 48 \" width= \"24 \">
<path clip-rule= \"evenodd \" d= \"M47.5 46.1l-2.8-11c1.8-3.3 2.8-7.1 2.8-11.1C47.5 11 37 .5 24 .5S.5 11 .5 24 11 47.5 24 47.5c4 0 7.8-1 11.1-2.8l11 2.8c.8.2 1.6-.6 1.4-1.4zm-3-22.1c0 4-1 7-2.6 10-.2.4-.3.9-.2 1.4l2.1 8.4-8.3-2.1c-.5-.1-1-.1-1.4.2-1.8 1-5.2 2.6-10 2.6-11.4 0-20.6-9.2-20.6-20.5S12.7 3.5 24 3.5 44.5 12.7 44.5 24z \" fill-rule= \"evenodd \"></path></svg></button></span>
<button class= \"wpO6b  \" type= \"button \">
<svg aria-label= \"Share Post \" class= \"_8-yf5  \" fill= \"#262626 \" height= \"24 \" viewBox= \"0 0 48 48 \" width= \"24 \">
<path d= \"M46.5 3.5h-45C.6 3.5.2 4.6.8 5.2l16 15.8 5.5 22.8c.2.9 1.4 1 1.8.3L47.4 5c.4-.7-.1-1.5-.9-1.5zm-40.1 3h33.5L19.1 18c-.4.2-.9.1-1.2-.2L6.4 6.5zm17.7 31.8l-4-16.6c-.1-.4.1-.9.5-1.1L41.5 9 24.1 38.3z \"></path>
<path d= \"M14.7 48.4l2.9-.7 \">
</path></svg></button>
<span class= \"wmtNn \">
<button class= \"wpO6b  \" type= \"button \">
<svg aria-label= \"Save \" class= \"_8-yf5  \" fill= \"#262626 \" height= \"24 \" viewBox= \"0 0 48 48 \" width= \"24 \">
<path d= \"M43.5 48c-.4 0-.8-.2-1.1-.4L24 29 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1zM24 26c.8 0 1.6.3 2.2.9l15.8 16V3H6v39.9l15.8-16c.6-.6 1.4-.9 2.2-.9z \"></path></svg></button></span></section>
<section class= \"EDfFK ygqzn \">
<div class= \"                   Igw0E     IwRSH      eGOV_     ybXk5   vwCYk                                                                                                                \">
<div class= \"Nm9Fw \"><button class= \"sqdOP yWX7d     _8A5w5     \" type= \"button \">
<span>785</span> likes</button></div></div></section>
<div class= \"EtaWk \">
<ul class= \"XQXOT \">";

$ex=explode("{\"node\":",$json);
$z=count($ex);
$i=1;

$ie=substr($ex[1],strpos($ex[1],",\"caption_is_edited\":")+21);
$ie=substr($ie,0,strpos($ie,","));

$text=substr($ex[1],strpos($ex[$i],"{\"text\":")+9);
$text=substr($text,0,strpos($text,"}")-1);

if (strpos($text,"#")) {
$q=explode("#",$text);
$h=count($q);
$text2="";
for($k=0;$k<$h;$k++) {
if ($k==0 && strpos($text,"#")!==FALSE) {
                 $text2.="<a class= \" \" href= \"/explore/tags/".substr($q[$k],0,substr($q[$k]," "))." \">#".substr($q[$k],0,substr($q[$k]," "))."</a>".substr($q,substr($q[$k]," "));}
else if ($k!=0) {$text2.="<a class= \" \" href= \"/explore/tags/".substr($q[$k],0,substr($q[$k]," "))." \">#".substr($q[$k],0,substr($q[$k]," "))."</a>".substr($q,substr($q[$k]," "));}
else {$text2.=$q[$k];}
}
$text=$text2;
}
if ($ie) {$text="<span class= \"Edited \">".$text."</span>";}
else {$text="<span class= \" \">".$text."</span>";}


$ud=substr($pre,strpos($pre,",\"uploadDate\":")+15);
$ud=substr($ud,0,strpos($ud,",")-1);
new DateTimeZone('UTC');
$time=Date("U",strtoTime($ud));
$diff=time()-$time;
$dt="";
if ($diff>60) {$dt=(int)($diff/60);$dt.="m";}
if ($diff>3600) {$dt=(int)($diff/3600);$dt.="h";}
if ($diff>3600*24) {$dt=(int)($diff/(60*60*24));$dt.="d";}
if ($diff>3600*24*7) {$dt=(int)($diff/(60*60*24*7));$dt.="w";}
//if ($diff>3600*24*30) {$dt=(int)($diff/(60*60*24*30));$dt.="M";}
if ($diff>3600*24*365) {$dt=(int)$diff/((60*60*24*365));$dt.="Y";}


$insert.="<div role= \"button \" class= \"ZyFrc \">
<li class= \"gElp9 rUo9f PpGvg  \" role= \"menuitem \">
<div class= \"P9YgZ \">
<div class= \"C7I1f X7jCj \">
<div class= \"RR-M- h5uC0 TKzGu  \" role= \"button \" tabindex= \"0 \">
<canvas class= \"CfWVH \" height= \"42 \" width= \"42 \" style= \"position: absolute; top: -5px; left: -5px; width: 42px; height: 42px; \"></canvas>
<span class= \"_2dbep  \" role= \"link \" tabindex= \"0 \" style= \"width: 32px; height: 32px; \">
<img alt= \"".$fn."'s profile picture \" class= \"_6q-tv \" src= \"".$durl." \"></span></div>
<div class= \"C4VMK \">
<h2 class= \"_6lAjh  \">
<a class= \"FPmhX notranslate  TlrDj \" title= \"".$fn." \" href= \"/".$fn."/ \">".$fn."</a>
<div class= \"                   Igw0E     IwRSH      eGOV_         _4EzTm                                       ItkAi                                                                        \">";
if ($iv) {$insert.="<span class= \"Szr5J  coreSpriteVerifiedBadgeSmall \" title= \"Verified \">Verified</span></div></h2>";}
$insert.=$text;
$insert.="<div class= \"                   Igw0E     IwRSH      eGOV_         _4EzTm   pjcA_                                                         aGBdT                                                   \">
<div class= \"_7UhW9  PIoXz       MMzan   _0PwGv       uL8Hv          \">
<time class= \"FH9sR Nzb55 \" datetime= \"".$ud." \" title= \"".date("F j, y, g:i a" ,$time)." \">".$dt."</time></div></div></div></div></div></li></div>
<li><div class= \"                   Igw0E     IwRSH        YBx95       _4EzTm      MGdpg                                                                                                      NUiEW   \" style= \"min-height: 40px; \">
<button class= \"dCJp8 afkep \">
<span aria-label= \"Load more comments \" class= \"glyphsSpriteCircle_add__outline__24__grey_9 u-__7 \"></span></button></div></li>
<ul class= \"Mr508 \">";

$ud2=$ud;$time2=$time;$dt2=$dt;
$isopen=false;
for($i=2;$i<$z;$i++) {

$text=substr($ex[$i],strpos($ex[$i],",\"text\":")+9);
$text=substr($text,0,strpos($text,"\",\""));

if (strpos($text,"#")) {
$q=explode("#",$text);
$h=count($q);
$text2="";
for($k=0;$k<$h;$k++) {
if ($k==0 && strpos($text,"#")!==FALSE) {
                 $text2.="<a class= \" \" href= \"/explore/tags/".substr($q[$k],0,substr($q[$k]," "))." \">#".substr($q[$k],0,substr($q[$k]," "))."</a>".substr($q,substr($q[$k]," "));}
else if ($k!=0) {$text2.="<a class= \" \" href= \"/explore/tags/".substr($q[$k],0,substr($q[$k]," "))." \">#".substr($q[$k],0,substr($q[$k]," "))."</a>".substr($q,substr($q[$k]," "));}
else {$text2.=$q[$k];}
}
$text=$text2;
}
$text="<span class= \" \">".$text."</span>";

$ud=substr($ex[$i],strpos($ex[$i],",\"created_at\":")+14);
$time=substr($ud,0,strpos($ud,","));
$ud=date("c",$time);
$diff=time()-$time;
$dt="";
if ($diff>60) {$dt=(int)($diff/60);$dt.="m";}
if ($diff>3600) {$dt=(int)($diff/3600);$dt.="h";}
if ($diff>3600*24) {$dt=(int)($diff/(60*60*24));$dt.="d";}
if ($diff>3600*24*7) {$dt=(int)($diff/(60*60*24*7));$dt.="w";}
//if ($diff>3600*24*30) {$dt=(int)($diff/(60*60*24*30));$dt.="M";}
if ($diff>3600*24*365) {$dt=(int)$diff/((60*60*24*365));$dt.="Y";}

$fn2=substr($ex[$i],strpos($ex[$i],",\"username\":\"")+13);
$fn2=substr($fn2,0,strpos($fn2,",")-2);
$durl2=substr($ex[$i],strpos($ex[$i],",\"profile_pic_url\":")+20);
$durl2=substr($durl2,0,strpos($durl2,"\",\""));

$re=-1;
if (strpos($ex[$i],"\"edge_threaded_comments\":")!==false){
$re=substr($ex[$i],strpos($ex[$i],",\"edge_threaded_comments\":{"));
$re=substr($re,strpos($re,"{\"count\":")+9);
$re=substr($re,0,strpos($re,","));
}

$re2=-1;
if ($i<$z-1) {
if (strpos($ex[$i+1],"\"edge_threaded_comments\":")!==false){
$re2=substr($ex[$i+1],strpos($ex[$i+1],",\"edge_threaded_comments\":{"));
$re2=substr($re2,strpos($re2,"{\"count\":")+9);
$re2=substr($re2,0,strpos($re2,","));
}}


//if ($re>=0) {
if ($isopen && $re2>=0) {$isopen=false;$insert.="</ul>";}
$insert.="<div role= \"button \" class= \"ZyFrc \"><li class= \"gElp9 rUo9f \" role= \"menuitem \"><div class= \"P9YgZ \"><div class= \"C7I1f X7jCj \"><div class= \"RR-M-  TKzGu  \" role= \"button \" tabindex= \"0 \"><canvas class= \"CfWVH \" height= \"42 \" width= \"42 \" style= \"position: absolute; top: -5px; left: -5px; width: 42px; height: 42px; \"></canvas><a class= \"_2dbep qNELH kIKUG \" href= \"/".$fn2."/ \" style= \"width: 32px; height: 32px; \">
<img alt= \"".$fn2."'s profile picture \" class= \"_6q-tv \" src= \"".$durl2." \"></a></div>
<div class= \"C4VMK \"><h3 class= \"_6lAjh  \">
<a class= \"FPmhX notranslate  TlrDj \" title= \"".$fn2." \" href= \"/".$fn2."/ \">".$fn2."</a></h3>".$text."
<div class= \"                   Igw0E     IwRSH      eGOV_         _4EzTm   pjcA_                                                         aGBdT                                                   \">
<div class= \"_7UhW9  PIoXz       MMzan   _0PwGv       uL8Hv          \">
<time class= \"FH9sR Nzb55 \" datetime= \"".$ud." \" title= \"".date("F j, y, g:i a" ,$time)." \">".$dt."</time>
<button class= \"FH9sR \">Reply</button></div></div></div></div></div></li></div></ul>";
//}
if ($re>0) {$insert.="<ul class= \"TCSYW \"><li class= \"_61Di1 \"><div class= \"                   Igw0E     IwRSH      eGOV_     ybXk5    _4EzTm                                                                                                               \"><button class= \"sqdOP yWX7d    y3zKF      \" type= \"button \"><div class= \"_7mCbS \"></div><span class= \"EizgU \">View replies (".$re.")</span></button></div></li>";$isopen=true;}
if ($z-1!=$i) {$insert.="<ul class= \"Mr508 \">";}
}//endfor


$insert.="</ul></div><div class= \"k_Q0X NnvRN \"><a class= \"c-Yi7 \" href= \"".$b." \"><time class= \"_1o9PC Nzb55 \" datetime= \"".$ud2." \" title= \"".date("F j, y, g:i a" ,$time2)." \">$dt2</time></a></div><section class= \"sH9wk  _JgwE  \"><div class= \"RxpZH \"><span><a href= \"/accounts/login/?next=%2Fp%2FB79f17iBT3B%2F&amp;source=post_comment_input \">Log in</a> to like or comment.</span></div></section></div>";

$insert.="<div class= \"MEAGs \"><button class= \"wpO6b  \" type= \"button \"><div class= \"                   Igw0E   rBNOH          YBx95       _4EzTm                                                                                                               \" style= \"height: 24px; width: 24px; \"><svg aria-label= \"More options \" class= \"_8-yf5  \" fill= \"#262626 \" height= \"16 \" viewBox= \"0 0 48 48 \" width= \"16 \"><circle clip-rule= \"evenodd \" cx= \"8 \" cy= \"24 \" fill-rule= \"evenodd \" r= \"4.5 \"></circle><circle clip-rule= \"evenodd \" cx= \"24 \" cy= \"24 \" fill-rule= \"evenodd \" r= \"4.5 \"></circle><circle clip-rule= \"evenodd \" cx= \"40 \" cy= \"24 \" fill-rule= \"evenodd \" r= \"4.5 \"></circle></svg></div></button></div></article></div></div></main><nav class= \"NXc7H jLuN9  X6gVd \"><div class= \"XajnB   \"></div><div class= \"_8MQSO  Cx7Bp \"><div class= \"_lz6s  \"><div class= \"MWDvN  \"><div class= \"oJZym \"><a class= \" \" href= \"/ \"><div class= \"                   Igw0E   rBNOH        eGOV_     ybXk5    _4EzTm                                                                                                               \"><svg aria-label= \"Instagram \" class= \"_8-yf5  \" fill= \"#262626 \" height= \"24 \" viewBox= \"0 0 48 48 \" width= \"24 \"><path d= \"M13.86.13A17 17 0 008 1.26 11 11 0 003.8 4 12.22 12.22 0 001 8.28 18 18 0 00-.11 14.1c-.13 2.55-.13 3.38-.13 9.9s0 7.32.13 9.9A18 18 0 001 39.72 11.43 11.43 0 003.8 44 12.17 12.17 0 008 46.74a17.75 17.75 0 005.82 1.13c2.55.13 3.38.13 9.9.13s7.32 0 9.9-.13a17.82 17.82 0 005.83-1.13A11.4 11.4 0 0043.72 44a11.94 11.94 0 002.78-4.24 17.7 17.7 0 001.13-5.82c.13-2.55.13-3.38.13-9.9s0-7.32-.13-9.9a17 17 0 00-1.13-5.86A11.31 11.31 0 0043.72 4a12.13 12.13 0 00-4.23-2.78A17.82 17.82 0 0033.66.13C31.11 0 30.28 0 23.76 0s-7.31 0-9.9.13m.2 43.37a13.17 13.17 0 01-4.47-.83 7.25 7.25 0 01-2.74-1.79 7.25 7.25 0 01-1.79-2.74 13.23 13.23 0 01-.83-4.47c-.1-2.52-.13-3.28-.13-9.7s0-7.15.13-9.7a12.78 12.78 0 01.83-4.44 7.37 7.37 0 011.79-2.75A7.35 7.35 0 019.59 5.3a13.17 13.17 0 014.47-.83c2.52-.1 3.28-.13 9.7-.13s7.15 0 9.7.13a12.78 12.78 0 014.44.83 7.82 7.82 0 014.53 4.53 13.12 13.12 0 01.83 4.44c.13 2.51.13 3.27.13 9.7s0 7.15-.13 9.7a13.23 13.23 0 01-.83 4.47 7.9 7.9 0 01-4.53 4.53 13 13 0 01-4.44.83c-2.51.1-3.28.13-9.7.13s-7.15 0-9.7-.13m19.63-32.34a2.88 2.88 0 102.88-2.88 2.89 2.89 0 00-2.88 2.88M11.45 24a12.32 12.32 0 1012.31-12.35A12.33 12.33 0 0011.45 24m4.33 0a8 8 0 118 8 8 8 0 01-8-8 \"></path></svg><div class= \"SvO5t \"></div><div class= \"cq2ai \"><img alt= \"Instagram \" class= \"s4Iyt \" src= \"/static/images/web/mobile_nav_type_logo.png/735145cfe0a4.png \" srcset= \"/static/images/web/mobile_nav_type_logo-2x.png/1b47f9d0e595.png 2x \"></div></div></a></div><div class= \"LWmhU _0aCwM \"><input class= \"XTCLo x3qfX  \" type= \"text \" autocapitalize= \"none \" placeholder= \"Search \" value= \" \"><div class= \"pbgfb Di7vw  \" role= \"button \" tabindex= \"0 \"><div class= \"eyXLr wUAXj  \"><span class= \"_6RZXI coreSpriteSearchIcon \"></span><span class= \"TqC_a \">Search</span></div></div></div><div class= \"ctQZg \"><div class= \"ZcHy5 \"><div class= \"vCZ2N \"><div class= \"      tHaIX             Igw0E     IwRSH   pmxbr     YBx95       _4EzTm                                      CIRqI                  IY_1_                           XfCBB             HcJZg        O1flK D8xaz  _7JkPY  TxciK  N9d2H ZUqME \" style= \"width: 100%; \"><button class= \"dCJp8 afkep xqRnw \"><span aria-label= \"Close \" class= \"glyphsSpriteGrey_Close u-__7 \"></span></button><div class= \"                   Igw0E   rBNOH     pmxbr     YBx95   ybXk5    _4EzTm                                                                                                               \" style= \"width: 903px; \"><div class= \" _41V_T                  Igw0E     IwRSH      eGOV_         _4EzTm                                                                                                               \" style= \"height: 56px; width: 56px; \"><span aria-label= \"Log In \" class= \"glyphsSpriteLogged_Out_QP_Glyph u-__7 \"></span></div><div class= \"oM3-t _RI9A \"><div class= \"          QzzMF         Igw0E     IwRSH      eGOV_         _4EzTm     _22l1                                                                                                          \"><div class= \"_7UhW9   xLCgt       qyrsm        h_zdq uL8Hv      M8ipN    \">Log In to Instagram</div></div><div class= \"_7UhW9   xLCgt      MMzan         h_zdq uL8Hv      M8ipN   hjZTB \">Log in to see photos and videos from friends and discover other accounts you'll love.</div></div><div class= \"                   Igw0E     IwRSH      eGOV_         _4EzTm                       n4cjz                                                                                        \" style= \"min-width: 112px; \"><div class= \"                   Igw0E     IwRSH      eGOV_         _4EzTm                                                           _49XvD                                                    \"><a class= \"sqdOP  L3NKy   y3zKF    ZIAjV  \" href= \"/accounts/login/?next=%2F&amp;source=logged_out_half_sheet \">Log In</a></div><div class= \"                   Igw0E     IwRSH      eGOV_         _4EzTm                                                           _49XvD                      lC6p0                              \"><a class= \"sqdOP yWX7d    y3zKF    ZIAjV  \" href= \"/accounts/emailsignup/ \">Sign Up</a></div></div></div></div></div><span class= \"r9-Os \"><a class= \"tdiEy \" href= \"/accounts/login/?next=%2Fp%2FB79f17iBT3B%2F&amp;source=desktop_nav \"><button class= \"sqdOP  L3NKy   y3zKF      \" type= \"button \">Log In</button></a><a class= \"tdiEy \" href= \"/accounts/emailsignup/ \">Sign Up</a></span></div></div></div></div></div></nav><footer class= \"_8Rna9  _3Laht  \" role= \"contentinfo \"><div class= \"iseBh  VWk7Y  \" style= \"max-width: 1012px; \"><nav class= \"uxKLF \"><ul class= \"ixdEe \"><li class= \"K5OFK \"><a class= \"l93RR \" href= \"https://about.instagram.com/about-us \" rel= \"nofollow noopener noreferrer \" target= \"_blank \">About us</a></li><li class= \"K5OFK \"><a class= \"l93RR \" href= \"https://help.instagram.com/ \">Help</a></li><li class= \"K5OFK \"><a class= \"l93RR \" href= \"https://instagram-press.com/ \">Press</a></li><li class= \"K5OFK \"><a class= \"l93RR \" href= \"/developer/ \">API</a></li><li class= \"K5OFK \"><a class= \"l93RR \" href= \"/about/jobs/ \">Jobs</a></li><li class= \"K5OFK \"><a class= \"l93RR \" href= \"/legal/privacy/ \">Privacy</a></li><li class= \"K5OFK \"><a class= \"l93RR _vfM2 \" href= \"/legal/terms/ \">Terms</a></li><li class= \"K5OFK \"><a class= \"l93RR \" href= \"/explore/locations/ \">Directory</a></li><li class= \"K5OFK \"><a class= \"l93RR \" href= \"/directory/profiles/ \">Profiles</a></li><li class= \"K5OFK \"><a class= \"l93RR \" href= \"/directory/hashtags/ \">Hashtags</a></li><li class= \"K5OFK \"><span class= \"_3G4x7  l93RR \">Language<select aria-label= \"Switch Display Language \" class= \"hztqj \"><option value= \"af \">Afrikaans</option><option value= \"cs \">Čeština</option><option value= \"da \">Dansk</option><option value= \"de \">Deutsch</option><option value= \"el \">Ελληνικά</option><option value= \"en \">English</option><option value= \"es \">Español (España)</option><option value= \"es-la \">Español</option><option value= \"fi \">Suomi</option><option value= \"fr \">Français</option><option value= \"id \">Bahasa Indonesia</option><option value= \"it \">Italiano</option><option value= \"ja \">日本語</option><option value= \"ko \">한국어</option><option value= \"ms \">Bahasa Melayu</option><option value= \"nb \">Norsk</option><option value= \"nl \">Nederlands</option><option value= \"pl \">Polski</option><option value= \"pt-br \">Português (Brasil)</option><option value= \"pt \">Português (Portugal)</option><option value= \"ru \">Русский</option><option value= \"sv \">Svenska</option><option value= \"th \">ภาษาไทย</option><option value= \"tl \">Filipino</option><option value= \"tr \">Türkçe</option><option value= \"zh-cn \">中文(简体)</option><option value= \"zh-tw \">中文(台灣)</option><option value= \"bn \">বাংলা</option><option value= \"gu \">ગુજરાતી</option><option value= \"hi \">हिन्दी</option><option value= \"hr \">Hrvatski</option><option value= \"hu \">Magyar</option><option value= \"kn \">ಕನ್ನಡ</option><option value= \"ml \">മലയാളം</option><option value= \"mr \">मराठी</option><option value= \"ne \">नेपाली</option><option value= \"pa \">ਪੰਜਾਬੀ</option><option value= \"si \">සිංහල</option><option value= \"sk \">Slovenčina</option><option value= \"ta \">தமிழ்</option><option value= \"te \">తెలుగు</option><option value= \"vi \">Tiếng Việt</option><option value= \"zh-hk \">中文(香港)</option><option value= \"bg \">Български</option><option value= \"fr-ca \">Français (Canada)</option><option value= \"ro \">Română</option><option value= \"sr \">Српски</option><option value= \"uk \">Українська</option></select></span></li></ul></nav><span class= \"DINPA \">© 2020 Instagram from Facebook</span></div></footer></section>";

$insert.="<link rel=\"stylesheet\" href=\"/static/bundles/es6/ConsumerUICommons.css/d1e3d67871d3.css\" type=\"text/css\" crossorigin=\"anonymous\">
<link rel=\"stylesheet\" href=\"/static/bundles/es6/ConsumerAsyncCommons.css/3c0ec6f379e9.css\" type=\"text/css\" crossorigin=\"anonymous\">
<link rel=\"stylesheet\" href=\"/static/bundles/es6/Consumer.css/b6eb327047b6.css\" type=\"text/css\" crossorigin=\"anonymous\">";
$insert.="<link rel=\"stylesheet\" href=\"/static/bundles/es6/PostComments.css/49c295fc85ad.css\" type=\"text/css\" crossorigin=\"anonymous\">";
$insert.="<link rel=\"stylesheet\" href=\"/static/bundles/es6/PostPageContainer.css/2f3f257852b7.css\" type=\"text/css\" crossorigin=\"anonymous\">";


$middle="<script  type=\"text/javascript\">\n//".$json."\n";
$r=$pre.$insert.$middle.$post;

}

else if ($a==="instagram.com") {
$c=str_replace("\u2019","'",$c);
$c=str_replace("\u0026","&",$c);
$c=str_replace("background: white;","",$c);
$head=substr($c,0,strpos("<head"));
$c=substr($c,strpos($c,"<head"));
$c=$head."<base href=\"https://www.instagram.com\">".$c;
$pre=substr($c,0,strpos($c,"window._sharedData ="));
$json=substr($c,strlen($pre));
$json=substr($json,0,strpos($json,"}}</script>")+2);
$post=substr($c,strlen($pre.$json));
$pre=substr($pre,0,strpos($pre,"\"react-root\"")+13);

$uid=substr($c,strpos($c,"/static/bundles/es6/ProfilePageContainer.js"));
$uid=substr($uid,strpos($uid,".js")+4);
$uid=substr($uid,0,strpos($uid,".js"));
$pid=substr($json,strpos($json,",\"id\":")+7);
$pid=substr($pid,0,strpos($pid,",")-1);
$str="https://www.instagram.com/static/bundles/es6/ProfilePageContainer.js/".$uid.".js";
$d=templatehelp($str);

//l is for stories the marker is " l="
//where do these user ids come from?
$l=substr($d,strpos($d," l=\"")+4);
$l=substr($l,0,strpos($l,"\""));

$str="https://www.instagram.com/graphql/query/?query_hash=".$l;
$str.="&variables=%7B\"user_id\"%3A\"".$pid;
$str.="\"%2C\"include_chaining\"%3Afalse%2C\"include_reel\"%3Afalse%2C\"include_suggested_users\"%3Afalse%2C\"";
$str.="include_highlight_reels\"%3Atrue%7D";
$l=templatehelp($str);
$ex=explode("\"node\":{",$l);
//this seems to be if we wanted more than the default 12 posted images.
//strange base64 number from end_curser.
//$ec=substr($json,strpos($json,",\"end_cursor\":")+14);
//$ec=substr($ec,0,strpos($ec,","));
//$e=explode("queryId:\"",$d);
//$m=substr($e[3],0,strpos($e[3],"\""));
//$f=templatehelp("https://www.instagram.com/graphql/query/?query_hash=".$m."&variables=%7B\"id\"%3A\"".$pid."\"%2C\"first\"%3A12%2C\"after\"%3A\"".#ec."\"%7D");
//$ex1=explode("{\"node\":{\"__typename\":",$f);

$insert="<form enctype=\"multipart/form-data\" method=\"POST\" role=\"presentation\">
<input accept=\"image/jpeg\" class=\"tb_sK\" type=\"file\"></form>
<section class=\"_9eogI E3X2T\"><div></div>
<main class=\"SCxLW  o64aR \" role=\"main\">
<div class=\"v9tJq \">
<header class=\"vtbgv \">
<div class=\"XjzKX\">
<div class=\"RR-M- \" role=\"button\" tabindex=\"0\">
<canvas class=\"CfWVH\" height=\"168\" width=\"168\" style=\"position: absolute; top: -9px; left: -9px; width: 168px; height: 168px;\"></canvas>
<span class=\"_2dbep \" role=\"link\" tabindex=\"0\" style=\"width: 150px; height: 150px;\">";
$fn=substr($json,strpos($json,",\"full_name\":")+14);
$fn=substr($fn,0,strpos($fn,"\",\""));
$bio=substr($json,strpos($json,"{\"biography\":")+14);
$eurl=substr($json,strpos($json,",\"external_url_linkshimmed\":")+29);
$eurl=substr($eurl,0,strpos($eurl,"\",\""));
$link=substr($json,strpos($json,",\"external_url\":")+17);
$link=substr($link,0,strpos($link,"\",\"")-1);
$bio=substr($bio,0,strpos($bio,"\",\"")-1);
$pp=substr($json,strpos($json,",\"profile_pic_url_hd\":")+23);
$pp=substr($pp,0,strpos($pp,"\",\""));
$iv=substr($json,strpos($json,",\"is_verified\":")+15);
$iv=substr($iv,0,strpos($iv,","));
$rep=substr($json,strpos($json,",\"edge_owner_to_timeline_media\":")+30);
$rep=substr($rep,strpos($rep,":{\"count\":")+10);
$rep=substr($rep,0,strpos($rep,","));
$rep4=$rep;
if ($rep>999) {$l=strlen($rep);$rep=substr($rep,0,$l-3).",".substr($rep,$l-3);$rep4=$rep;}
$rgc=substr($json,strpos($json,",\"edge_followed_by\":")+21);
$rgc=substr($rgc,strpos($rgc,":{\"count\":")+10);
$rgc=substr($rgc,0,strpos($rgc,",")-1);
$rgc2=$rgc;$rgc3=$rgc;$rgc4=$rgc;
if ($rgc>999) {$rgc3=(int)($rgc/1000);$rgc3.="k";
$l=strlen($rgc);$rgc2=substr($rgc,0,$l-3).",".substr($rgc,$l-3);
$rgc4=$rgc2;
if ($rgc>9999) {$rgc4=$rgc3;}}
$fv=substr($json,strpos($json,",\"edge_followed\":")+18);
$fv=substr($fv,strpos($fv,":{\"count\":")+10);
$fv=substr($fv,0,strpos($fv,",")-1);
$fv2=$fv;$fv3=$fv;$fv4=$fv;
if ($fv>999) {$fv3=(int)($fv/1000);$fv3.="k";
$l=strlen($fv);$fv2=substr($fv,0,$l-3).",".substr($fv,$l-3);
$fv4=$fv2;
if ($fv>9999) {$fv4=$fv3;}}

//echo "<br>fn".$fn;
//echo "<br>bio".$bio;
//echo "<br>eurl".$eurl;
//echo "<br>link".$link;
//echo "<br>pp".$pp;
//echo "<br>iv".$iv;
//echo "<br>rep".$rep;
//echo "<br>rep2:".$rep2;
//echo "<br>rep3:".$rep3;
//echo "<br>rgc".$rgc;
//echo "<br>rgc2:".$rgc2;
//echo "<br>rgc3:".$rgc3;
//echo "<br>fv".$fv;
//echo "<br>fv2:".$fv2;
//echo "<br>fv3:".$fv3;

$insert.="<img alt=\"".$fn."'s profile picture\" class=\"_6q-tv\" src=\"".$pp."\"></span></div></div>
<section class=\"zwlfE\"><div class=\"nZSzR\"><h1 class=\"_7UhW9       fKFbl yUEEX   KV-D4            fDxYl     \">".$fn."</h1>";

if ($iv) {
$insert.="<span class=\"mrEK_ Szr5J coreSpriteVerifiedBadge \" title=\"Verified\">Verified</span>";}

$insert.="<a class=\"BY3EC \" rel=\"nofollow\" href=\"/accounts/login/?next=%2F".$fn."%2F&amp;source=follow\">
<button class=\"sqdOP  L3NKy   y3zKF     \" type=\"button\">Follow</button></a></div>
<ul class=\"k9GMp \"><li class=\"Y8-fY \">
<a class=\"-nal3 \" href=\"/accounts/login/?next=%2F".$fn."%2F&amp;source=profile_posts\">
<span class=\"g47SY \">".$rep."</span> posts</a></li><li class=\"Y8-fY \">
<a class=\"-nal3 \" href=\"/accounts/login/?next=%2F".$fn."%2Ffollowers%2F&amp;source=followed_by_list\">
<span class=\"g47SY \" title=\"".$rgc3."\">".$fv4."</span> followers</a></li>
<li class=\"Y8-fY \">
<a class=\"-nal3 \" href=\"/accounts/login/?next=%2F".$fn."%2Ffollowing%2F&amp;source=follows_list\">
<span class=\"g47SY \">".$rgc4."</span> following</a></li></ul>
<div class=\"-vDIg\"><h1 class=\"rhpdm\">".$fn."</h1><br>
<span>".$bio."</span> <a class=\"yLUwa\" href=\"".$eurl."\" rel=\"me nofollow noopener noreferrer\" target=\"_blank\">".$link."</a></div></section></header>
<div class=\"_4bSq7\"><div class=\"zRsZI\"><div class=\"NgKI_\"><div class=\"MreMs\" tabindex=\"0\" style=\"transition-duration: 0s; transform: translateX(0px);\">
<div class=\"qqm6D\"><ul class=\"YlNGR\" style=\"padding-left: 14px; padding-right: 24px;\">";

//repeat

$z=count($ex);
for($i=1;$i<$z;$i++) {
$title=substr($ex[$i],strpos($ex[$i],"},\"title\":")+10);
$title=substr($title,0,strpos($title,"}")-1);
$pimg=substr($ex[$i],strpos($ex[$i],"},\"cover_media_cropped_thumbnail\":")+42);
$pimg=substr($pimg,0,strpos($pimg,"}")-1);
$insert.="<li class=\"_-1_m6\" style=\"opacity: 1; width: 130px;\">
<div class=\"bsGjF\" style=\"margin-left: 10px; width: 120px;\">
<div class=\"_3D7yK\">
<div aria-label=\"Open Stories\" class=\"aoVrC D1yaK\" role=\"button\" tabindex=\"0\">
<canvas height=\"87\" width=\"87\" style=\"position: absolute; top: -5px; left: -5px; width: 87px; height: 87px;\"></canvas>
<div class=\"tUtVM\" style=\"width: 77px; height: 77px;\">
<img class=\"NCYx-\" src=\"".$pimg."\" alt=\"".$title."'s profile picture\"></div></div>
<div class=\"eXle2\" role=\"menuitem\" tabindex=\"0\">".$title."</div></div></div></li>";
}



//new stuff
$insert.="</ul></div></div></div></div></div>
<div class=\"fx7hk\"><a class=\"_9VEo1 T-jvg\" href=\"/".$fn."/\"><span class=\"smsjF\"><div class=\" coreSpriteDesktopPhotoGridActive\"></div>
<span class=\"PJXu4\">Posts</span></span></a>
<a class=\"_9VEo1 \" href=\"/accounts/login/?next=%2F".$fn."%2Ftagged%2F&amp;source=profile_tagged_tab\">
<span class=\"qzihg\"><div class=\"coreSpriteDesktopProfileTagged \"></div>
<span class=\"_08DtY\">Tagged</span></span></a></div>
<div class=\" _2z6nI\">
<article class=\"ySN3v\"><div>
<div style=\"flex-direction: column; padding-bottom: 0px; padding-top: 0px;\">";


//repeated stuff
$ex2=explode(",\"shortcode\":\"",$json);
$z=count($ex2);
for($i=1;$i<$z;$i++) {
if (($i%3)==1) {$insert.="<div class=\"Nnq7C weEfm\">";}
$sc=substr($ex2[$i],0,strpos($ex2[$i],",")-1);
$ac=substr($ex2[$i],strpos($ex2[$i],",\"accessibility_caption\":")+26);
$ac=substr($ac,0,strpos($ac,"}")-1);
$timg=substr($ex2[$i],strpos($ex2[$i],",\"thumbnail_src\":\"")+18);
$timg=substr($timg,0,strpos($timg,",")-1);
//echo $timg;

$tr=substr($ex2[$i],strpos($ex2[$i],",\"thumbnail_resources\":")+24);
$tr=substr($tr,0,strpos($tr,"]"));
$ex3=explode("{\"src\":\"",$tr);
$insert.="<div class=\"v1Nh3 kIKUG  _bz0w\">
<a href=\"/p/".$sc."/\"><div class=\"eLAPa\"><div class=\"KL4Bh\">
<img alt=\"".$ac."\" class=\"FFVAD\" decoding=\"auto\" style=\"object-fit: cover;\" sizes=\"237.671875px\" srcset=";
$y=count($ex3);
for($j=1;$j<$y;$j++) {
//echo "<br><br>".$ex3[$j];
$timg2=substr($ex3[$j],0,strpos($ex3[$j],",")-1);
//echo $timg2;
$tsize=substr($ex3[$j],strlen($timg2));
$tsize=substr($tsize,strpos($tsize,":"));
$tsize=substr($tsize,1,strpos($tsize,",")-1);
$tsize.="w";
$insert.=$timg2." ".$tsize.($j-1==$y?"\" ":",");
}
//$time=str_replace("\"","",$timg);
$insert.=" src=\"".$timg."\"></div>
<div class=\"_9AhH0\"></div></div></a></div>";
if ($i%3==0) {$insert.="</div>";}
}
//new stuff
//twirly thing;
$insert.="</div></div><!--div class=\"_4emnV\"--><!--div class=\"                   Igw0E     IwRSH        YBx95       _4EzTm                                                                                                               _9qQ0O ZUqME\" style=\"height: 32px; width: 32px;\"--><!--svg aria-label=\"Loading...\" class=\"  By4nA\" viewBox=\"0 0 100 100\"--><!--rect fill=\"#555555\" height=\"6\" opacity=\"0\" rx=\"3\" ry=\"3\" transform=\"rotate(-90 50 50)\" width=\"25\" x=\"72\" y=\"47\"--><!--/rect--><!--rect fill=\"#555555\" height=\"6\" opacity=\"0.08333333333333333\" rx=\"3\" ry=\"3\" transform=\"rotate(-60 50 50)\" width=\"25\" x=\"72\" y=\"47\"--><!--/rect--><!--rect fill=\"#555555\" height=\"6\" opacity=\"0.16666666666666666\" rx=\"3\" ry=\"3\" transform=\"rotate(-30 50 50)\" width=\"25\" x=\"72\" y=\"47\"--><!--/rect--><!--rect fill=\"#555555\" height=\"6\" opacity=\"0.25\" rx=\"3\" ry=\"3\" transform=\"rotate(0 50 50)\" width=\"25\" x=\"72\" y=\"47\"--><!--/rect--><!--rect fill=\"#555555\" height=\"6\" opacity=\"0.3333333333333333\" rx=\"3\" ry=\"3\" transform=\"rotate(30 50 50)\" width=\"25\" x=\"72\" y=\"47\"--><!--/rect--><!--rect fill=\"#555555\" height=\"6\" opacity=\"0.4166666666666667\" rx=\"3\" ry=\"3\" transform=\"rotate(60 50 50)\" width=\"25\" x=\"72\" y=\"47\"--><!--/rect--><!--rect fill=\"#555555\" height=\"6\" opacity=\"0.5\" rx=\"3\" ry=\"3\" transform=\"rotate(90 50 50)\" width=\"25\" x=\"72\" y=\"47\"--><!--/rect--><!--rect fill=\"#555555\" height=\"6\" opacity=\"0.5833333333333334\" rx=\"3\" ry=\"3\" transform=\"rotate(120 50 50)\" width=\"25\" x=\"72\" y=\"47\"--><!--/rect--><!--rect fill=\"#555555\" height=\"6\" opacity=\"0.6666666666666666\" rx=\"3\" ry=\"3\" transform=\"rotate(150 50 50)\" width=\"25\" x=\"72\" y=\"47\"--><!--/rect--><!--rect fill=\"#555555\" height=\"6\" opacity=\"0.75\" rx=\"3\" ry=\"3\" transform=\"rotate(180 50 50)\" width=\"25\" x=\"72\" y=\"47\"--><!--/rect--><!--rect fill=\"#555555\" height=\"6\" opacity=\"0.8333333333333334\" rx=\"3\" ry=\"3\" transform=\"rotate(210 50 50)\" width=\"25\" x=\"72\" y=\"47\"--><!--/rect--><!--rect fill=\"#555555\" height=\"6\" opacity=\"0.9166666666666666\" rx=\"3\" ry=\"3\" transform=\"rotate(240 50 50)\" width=\"25\" x=\"72\" y=\"47\"--></rect><!--/svg--><!--/div--><!--/div-->";
$insert.="</article></div></div></main><nav class=\"NXc7H jLuN9  X6gVd\"><div class=\"XajnB  \"></div><div class=\"_8MQSO  Cx7Bp\"><div class=\"_lz6s \"><div class=\"MWDvN \"><div class=\"oJZym\"><a class=\"\" href=\"/\"><div class=\"                   Igw0E   rBNOH        eGOV_     ybXk5    _4EzTm                                                                                                              \"><svg aria-label=\"Instagram\" class=\"_8-yf5 \" fill=\"#262626\" height=\"24\" viewBox=\"0 0 48 48\" width=\"24\"><path d=\"M13.86.13A17 17 0 008 1.26 11 11 0 003.8 4 12.22 12.22 0 001 8.28 18 18 0 00-.11 14.1c-.13 2.55-.13 3.38-.13 9.9s0 7.32.13 9.9A18 18 0 001 39.72 11.43 11.43 0 003.8 44 12.17 12.17 0 008 46.74a17.75 17.75 0 005.82 1.13c2.55.13 3.38.13 9.9.13s7.32 0 9.9-.13a17.82 17.82 0 005.83-1.13A11.4 11.4 0 0043.72 44a11.94 11.94 0 002.78-4.24 17.7 17.7 0 001.13-5.82c.13-2.55.13-3.38.13-9.9s0-7.32-.13-9.9a17 17 0 00-1.13-5.86A11.31 11.31 0 0043.72 4a12.13 12.13 0 00-4.23-2.78A17.82 17.82 0 0033.66.13C31.11 0 30.28 0 23.76 0s-7.31 0-9.9.13m.2 43.37a13.17 13.17 0 01-4.47-.83 7.25 7.25 0 01-2.74-1.79 7.25 7.25 0 01-1.79-2.74 13.23 13.23 0 01-.83-4.47c-.1-2.52-.13-3.28-.13-9.7s0-7.15.13-9.7a12.78 12.78 0 01.83-4.44 7.37 7.37 0 011.79-2.75A7.35 7.35 0 019.59 5.3a13.17 13.17 0 014.47-.83c2.52-.1 3.28-.13 9.7-.13s7.15 0 9.7.13a12.78 12.78 0 014.44.83 7.82 7.82 0 014.53 4.53 13.12 13.12 0 01.83 4.44c.13 2.51.13 3.27.13 9.7s0 7.15-.13 9.7a13.23 13.23 0 01-.83 4.47 7.9 7.9 0 01-4.53 4.53 13 13 0 01-4.44.83c-2.51.1-3.28.13-9.7.13s-7.15 0-9.7-.13m19.63-32.34a2.88 2.88 0 102.88-2.88 2.89 2.89 0 00-2.88 2.88M11.45 24a12.32 12.32 0 1012.31-12.35A12.33 12.33 0 0011.45 24m4.33 0a8 8 0 118 8 8 8 0 01-8-8\"></path></svg><div class=\"SvO5t\"></div><div class=\"cq2ai\"><img alt=\"Instagram\" class=\"s4Iyt\" src=\"/static/images/web/mobile_nav_type_logo.png/735145cfe0a4.png\" srcset=\"/static/images/web/mobile_nav_type_logo-2x.png/1b47f9d0e595.png 2x\"></div></div></a></div><div class=\"LWmhU _0aCwM\"><input class=\"XTCLo x3qfX \" type=\"text\" autocapitalize=\"none\" placeholder=\"Search\" value=\"\"><div class=\"pbgfb Di7vw \" role=\"button\" tabindex=\"0\"><div class=\"eyXLr wUAXj \"><span class=\"_6RZXI coreSpriteSearchIcon\"></span><span class=\"TqC_a\">Search</span></div></div></div><div class=\"ctQZg\"><div class=\"ZcHy5\"><div class=\"vCZ2N\"><div class=\"      tHaIX             Igw0E     IwRSH   pmxbr     YBx95       _4EzTm                                      CIRqI                  IY_1_                           XfCBB             HcJZg        O1flK D8xaz  _7JkPY  TxciK  N9d2H ZUqME\" style=\"width: 100%;\"><button class=\"dCJp8 afkep xqRnw\"><span aria-label=\"Close\" class=\"glyphsSpriteGrey_Close u-__7\"></span></button><div class=\"                   Igw0E   rBNOH     pmxbr     YBx95   ybXk5    _4EzTm                                                                                                              \" style=\"width: 903px;\"><div class=\" _41V_T                  Igw0E     IwRSH      eGOV_         _4EzTm                                                                                                              \" style=\"height: 56px; width: 56px;\"><span aria-label=\"Log In\" class=\"glyphsSpriteLogged_Out_QP_Glyph u-__7\"></span></div><div class=\"oM3-t _RI9A\"><div class=\"          QzzMF         Igw0E     IwRSH      eGOV_         _4EzTm     _22l1                                                                                                         \"><div class=\"_7UhW9   xLCgt       qyrsm        h_zdq uL8Hv      M8ipN   \">Log In to Instagram</div></div><div class=\"_7UhW9   xLCgt      MMzan         h_zdq uL8Hv      M8ipN   hjZTB\">Log in to see photos and videos from friends and discover other accounts you'll love.</div></div><div class=\"                   Igw0E     IwRSH      eGOV_         _4EzTm                       n4cjz                                                                                       \" style=\"min-width: 112px;\"><div class=\"                   Igw0E     IwRSH      eGOV_         _4EzTm                                                           _49XvD                                                   \"><a class=\"sqdOP  L3NKy   y3zKF    ZIAjV \" href=\"/accounts/login/?next=%2F&amp;source=logged_out_half_sheet\">Log In</a></div><div class=\"                   Igw0E     IwRSH      eGOV_         _4EzTm                                                           _49XvD                      lC6p0                             \"><a class=\"sqdOP yWX7d    y3zKF    ZIAjV \" href=\"/accounts/emailsignup/\">Sign Up</a></div></div></div></div></div><span class=\"r9-Os\"><a class=\"tdiEy\" href=\"/accounts/login/?next=%2F".strToLower($fn)."%2F&amp;source=desktop_nav\"><button class=\"sqdOP  L3NKy   y3zKF     \" type=\"button\">Log In</button></a><a class=\"tdiEy\" href=\"/accounts/emailsignup/\">Sign Up</a></span></div></div></div></div></div></nav><footer class=\"_8Rna9  _3Laht \" role=\"contentinfo\"><div class=\"iseBh  VWk7Y \" style=\"max-width: 1012px;\"><nav class=\"uxKLF\"><ul class=\"ixdEe\"><li class=\"K5OFK\"><a class=\"l93RR\" href=\"https://about.instagram.com/about-us\" rel=\"nofollow noopener noreferrer\" target=\"_blank\">About us</a></li><li class=\"K5OFK\"><a class=\"l93RR\" href=\"https://help.instagram.com/\">Help</a></li><li class=\"K5OFK\"><a class=\"l93RR\" href=\"https://instagram-press.com/\">Press</a></li><li class=\"K5OFK\"><a class=\"l93RR\" href=\"/developer/\">API</a></li><li class=\"K5OFK\"><a class=\"l93RR\" href=\"/about/jobs/\">Jobs</a></li><li class=\"K5OFK\"><a class=\"l93RR\" href=\"/legal/privacy/\">Privacy</a></li><li class=\"K5OFK\"><a class=\"l93RR _vfM2\" href=\"/legal/terms/\">Terms</a></li><li class=\"K5OFK\"><a class=\"l93RR\" href=\"/explore/locations/\">Directory</a></li><li class=\"K5OFK\"><a class=\"l93RR\" href=\"/directory/profiles/\">Profiles</a></li><li class=\"K5OFK\"><a class=\"l93RR\" href=\"/directory/hashtags/\">Hashtags</a></li><li class=\"K5OFK\"><span class=\"_3G4x7  l93RR\">Language<select aria-label=\"Switch Display Language\" class=\"hztqj\"><option value=\"af\">Afrikaans</option><option value=\"cs\">Čeština</option><option value=\"da\">Dansk</option><option value=\"de\">Deutsch</option><option value=\"el\">Ελληνικά</option><option value=\"en\">English</option><option value=\"es\">Español (España)</option><option value=\"es-la\">Español</option><option value=\"fi\">Suomi</option><option value=\"fr\">Français</option><option value=\"id\">Bahasa Indonesia</option><option value=\"it\">Italiano</option><option value=\"ja\">日本語</option><option value=\"ko\">한국어</option><option value=\"ms\">Bahasa Melayu</option><option value=\"nb\">Norsk</option><option value=\"nl\">Nederlands</option><option value=\"pl\">Polski</option><option value=\"pt-br\">Português (Brasil)</option><option value=\"pt\">Português (Portugal)</option><option value=\"ru\">Русский</option><option value=\"sv\">Svenska</option><option value=\"th\">ภาษาไทย</option><option value=\"tl\">Filipino</option><option value=\"tr\">Türkçe</option><option value=\"zh-cn\">中文(简体)</option><option value=\"zh-tw\">中文(台灣)</option><option value=\"bn\">বাংলা</option><option value=\"gu\">ગુજરાતી</option><option value=\"hi\">हिन्दी</option><option value=\"hr\">Hrvatski</option><option value=\"hu\">Magyar</option><option value=\"kn\">ಕನ್ನಡ</option><option value=\"ml\">മലയാളം</option><option value=\"mr\">मराठी</option><option value=\"ne\">नेपाली</option><option value=\"pa\">ਪੰਜਾਬੀ</option><option value=\"si\">සිංහල</option><option value=\"sk\">Slovenčina</option><option value=\"ta\">தமிழ்</option><option value=\"te\">తెలుగు</option><option value=\"vi\">Tiếng Việt</option><option value=\"zh-hk\">中文(香港)</option><option value=\"bg\">Български</option><option value=\"fr-ca\">Français (Canada)</option><option value=\"ro\">Română</option><option value=\"sr\">Српски</option><option value=\"uk\">Українська</option></select></span></li></ul></nav><span class=\"DINPA\">© 2020 Instagram from Facebook</span></div></footer></section></div>";


$insert.="<link rel=\"stylesheet\" href=\"/static/bundles/es6/ConsumerUICommons.css/d1e3d67871d3.css\" type=\"text/css\" crossorigin=\"anonymous\">
<link rel=\"stylesheet\" href=\"/static/bundles/es6/ConsumerAsyncCommons.css/3c0ec6f379e9.css\" type=\"text/css\" crossorigin=\"anonymous\">
<link rel=\"stylesheet\" href=\"/static/bundles/es6/Consumer.css/b6eb327047b6.css\" type=\"text/css\" crossorigin=\"anonymous\">
<link rel=\"stylesheet\" href=\"/static/bundles/es6/ProfilePageContainer.css/9801fa221f95.css\"type=\"text/css\" crossorigin=\"anonymous\">";



//$ppre=substr($c,strpos($c,"</div>"));
//$pre.=$ppre;
//echo "strlen:".strlen($pre)." ".strlen($json)." ".strlen($post)." ".strlen($c);
$middle="<script  type=\"text/javascript\">\n//".$json."\n";
$r=$pre.$insert.$middle.$post;
}

else if ($a==="cnn.com"){
$pre=substr($c,0,strpos($c,"=\"l-container\">")+21);
//$fill="<div style=\"display: none;\" id=\"lightningjs-usabilla_live\"><div><iframe frameborder=\"0\" id=\"lightningjs-frame-usabilla_live\"></iframe></div></div>";
$trending=substr($d,strpos($d,"<"));

$trending=substr($trending,0,strpos($trending,"</section>")+10);
$trending=str_replace("\\n","\n",$trending);
$trending=str_replace("\\\"","\"",$trending);
$e=str_replace("\\n","\n",$e);
$e=str_replace("\\\"","\"",$e);
$e=cnn($e);
$post=substr($c,strpos($c,"=\"l-container\">")+21);
$page1=substr($e,0,strpos($e,"</section>")+10);
$rest=substr($e,strpos($e,"</section>")+1);
$page1=substr($page1,strpos($page1,"<"));
$page2=substr($rest,strpos($rest,"<"));
$page2=substr($page2,0,strpos($page2,"</section>")+10);
$rest=substr($rest,strpos($test,"</section>}")+1);
$page3=substr($rest,strpos($rest,"<"));
$page3=substr($page3,0,strpos($page3,"</section>")+10);
$rest=substr($rest,strpos($test,"</section>")+1);
$page4=substr($rest,strpos($rest,"<"));
$page4=substr($page4,0,strpos($page4,"</section>")+10);
$rest=substr($rest,strpos($test,"</section>")+1);
$page5=substr($rest,strpos($rest,"<"));
$page5=substr($page5,0,strpos($page5,"</section>")+10);
$rest=substr($rest,strpos($test,"</section>")+1);
$page6=substr($rest,strpos($rest,"<"));
$page6=substr($page6,0,strpos($page6,"</section>")+10);
$rest=substr($rest,strpos($test,"</section>")+1);
$page7=substr($rest,strpos($rest,"<"));
$page7=substr($page7,0,strpos($page7,"</section>")+10);
$rest=substr($rest,strpos($test,"</section>")+1);
$page8=substr($rest,strpos($rest,"<"));
$page8=substr($page8,0,strpos($page8,"</section>")+10);

$r=$pre.$trending.$page1.$page2.$page3.$page4.$page5.$page6.$page7.$page8.$post;
}//end else cnn
else if ($a==="youtube.com") {
//just for testing;
return $c;
$ll=$c;
$pre=substr($ll,0,strpos($ll,"div id=\"player-api")-1);
$pre=substr($pre,0,strpos($pre,"<head"))."<base href=https://youtube.com>".substr($pre,strpos($pre,"<head"));
$post=substr($ll,strpos($ll,"div id=\"player-api"));
$post=substr($post,strpos($post,"<script"));
$post=substr($post,strpos($post,"</div"));
$r=$pre.$post;
}
else if ($a==="facebook.com" && strpos($b,"/video/")) {
if (strpos($c,"<div class=\"_5hn6\" id=\"u_0_f\"") && strpos($c,"<div class=\"clearfix\" id=\"content_container\">")) {
$pre=substr($c,0,strpos($c,"<div class=\"_5hn6\" id=\"u_0_f\""));
$post=substr($c,strpos($c,"<div class=\"clearfix\" id=\"content_container\">"));
}
else {
//some mechanism to alert facebook /video has changed.
$r=$c;}
}
else if ($a==="pinterest.com" && $b==="/user/") {
require("pinstyle.php");
$head=substr($c,0,strpos($c,"<head"));
$c=substr($c,strpos($c,"<head"));
$c=$head."<base href=\"https://www.pinterest.com\">".$c;
$pre=substr($c,0,strpos($c,"<div id=\"__PWS_ROOT__\" data-reactcontainer"));
$post=substr($c,strpos($c,"<div id=\"__PWS_ROOT__\" data-reactcontainer"));
$pre.=substr($post,0,strpos($post,"</div"));
$post=substr($post,strpos($post,"</div"));
$json=substr($post,strpos($post,"{"));
$json=substr($json,0,strrpos($json,"</script>"));
$post="</body></html>";

$fn=substr($json,strpos($json,",\"username\":\"")+13);
$fn=substr($fn,0,strpos($fn,",")-1);
$fn2=substr($json,strpos($json,",\"full_name\":\"")+14);
$fn2=substr($fn2,0,strpos($fn2,",")-1);

$path=substr($json,strpos($json,",\"slug\":\"")+9);
$path=substr($path,0,strpos($path,",")-1);
$id=substr($json,strpos($json,",\"id\":\"")+7);
$id=substr($id,0,strpos($id,",")-1);
$id2=substr($json,strpos($json,"\"id\":")+6);
$id2=substr($id2,strpos($id2,"\"id\":")+6);
$id2=substr($id2,0,strpos($id2,",")-1);
$path2=substr($json,strpos($json,"\"url\":\"/".$fn)+9);
$path2=substr($path2,0,strpos($path2,",")-1);
$board=substr($json,strpos($json,"\"board\":{")+0);
$name=substr($board,strpos($board,"\"name\":\"")+7);
$name=substr($name,0,strpos($name,"\","));
$fv=substr($json,strpos($json,"\"repin_count\":")+14);
$fv=substr($fv,0,strpos($fv,","));
if ($fv>1000) {$fv=((int)($fv/1000));$fv.="k";}

$links="";
if (strpos($json,",\"annotations_with_links\":{")!==FALSE) {
$links=substr($json,strpos($json,",\"annotations_with_links\":{")+27);
$links=substr($links,0,strpos($links,"}}"));
}
else if (strpos($json,"{\"annotations_with_links\":{")!==FALSE) {
$links=substr($json,strpos($json,"{\"annotations_with_links\":{")+27);
$links=substr($links,0,strpos($links,"}}"));
}
else {
$links=substr($json,strpos($json,",\"annotations_with_links\":")+27);
$links=substr($links,0,strpos($links,"}}"));
}

$insert.=$sty9;
$insert.="<div class=\"zI7 iyn Hsu\">
<div style=\"margin-top: 120px;\">
<div class=\"BrioProfileHeaderWrapper\">
<div class=\"fixedHeader fixed\" style=\"top: 64px; position: fixed;\">
<div class=\"Jea mQ8 zI7 iyn Hsu\">
<div class=\"zI7 iyn Hsu\" style=\"max-width: 800px; width: 100%;\">
<div data-test-id=\"fixedProfileHeader\" class=\"Jea X6t gjz hA- hDW zI7 iyn Hsu\" style=\"width: 100%;\">
<div class=\"Jea X0f d5Q gjz hA- ujU wYR zI7 iyn Hsu\"><div class=\"boardName\">
<div class=\"Jea gjz zI7 iyn Hsu\">
<div class=\"FNs zI7 iyn Hsu\">
<div class=\"INd XiG qJc zI7 iyn Hsu\" style=\"width: 24px; height: 24px;\">
<div class=\"Pj7 sLG XiG pJI INd m1e\">
<div class=\"XiG zI7 iyn Hsu\" style=\"background-color: rgb(239, 239, 239); padding-bottom: 100%;\">";

$purl=substr($json,strpos($json,"\"image_xlarge_url\":")+20);
$purl=substr($purl,0,strpos($purl,"\","));

$insert.="<img alt=\"".$fn2."\" class=\"hCL kVc L4E MIw\" importance=\"auto\" loading=\"auto\" src=\"".$purl."\"></div>
<div class=\"KPc MIw ojN Rym p6V QLY\"></div></div></div></div><div class=\"FNs wYR zI7 iyn Hsu\">

<span class=\"tBJ dyH iFc SMy _S5 pBj tg7 IZT mWe z-6\" title=\"".$fn2."\">".$fn2."</span></div></div></div></div>
<div class=\"Jea gRy jx- zI7 iyn Hsu\">
<button class=\"RCK Hsu mix Vxj aZc GmH adn Il7 Jrn hNT iyn BG7 NTm KhY\" type=\"button\">
<div class=\"tBJ dyH iFc SMy yTZ erh tg7 mWe\">Follow</div></button></div></div></div></div></div>
<div class=\"Jea mQ8 zI7 iyn Hsu\">
<div class=\"zI7 iyn Hsu\" style=\"max-width: 800px; width: 100%;\"><div>
<div class=\"Fje Jea zI7 iyn Hsu\">
<div class=\"TPW xEW\">
<div class=\"QMJ wYR zI7 iyn Hsu\" style=\"margin-top: 10px;\">
<div class=\"AvatarScroller step0\">
<div class=\"ShadowedAvatarWrapper\">

<img alt=\"".$fn2."\" src=\"".$purl."\" style=\"width: 168px; height: 168px; border-radius: 84px;\"></div></div></div></div>
<div class=\"X0f z-m Qfr zI7 iyn Hsu\">
<div class=\"Fje Jea MMr b8T kzZ zI7 iyn Hsu\">
<div class=\"VxL aoh ojd ujU wYR zI7 iyn Hsu\">

<h1 class=\"lH1 dyH iFc SMy kON E5p pBj IZT\">".$fn2."</h1>
<div class=\"Jea KO4 zI7 iyn Hsu\">
<div class=\"Jea zI7 iyn Hsu\">";


$fv=substr($json,strpos($json,"\"pinterestapp:followers\":")+25);
$fv=substr($fv,0,strpos($fv,","));

$rep=substr($json,strpos($json,"\"pinterestapp:following\":")+25);
$rep=substr($rep,0,strpos($rep,","));

$insert.="<span class=\"tBJ dyH iFc SMy _S5 pBj DrD mWe\">".$fv."</span>&nbsp;
<span class=\"tBJ dyH iFc SMy _S5 pBj DrD swG\">Followers</span>
<div class=\"Jea zI7 iyn Hsu\"><div class=\"XTf ocu zI7 iyn Hsu\">
<div class=\"tBJ dyH iFc SMy yTZ pBj DrD IZT swG\">•</div></div>
<span class=\"tBJ dyH iFc SMy _S5 pBj DrD mWe\">".$rep."</span>&nbsp;
<span class=\"tBJ dyH iFc SMy _S5 pBj DrD swG\">Following</span></div></div></div>
<div class=\"Jea hjj zI7 iyn Hsu\">
<div class=\"tBJ dyH iFc SMy _S5 pBj DrD IZT mWe\"></div></div>
<div class=\"hjj zI7 iyn Hsu\">
<div class=\"tBJ dyH iFc SMy _S5 pBj DrD IZT swG\"></div></div></div></div></div></div></div></div></div></div>
<div class=\"zI7 iyn Hsu\">
<div class=\"Jea Z2K vks zI7 iyn Hsu\"><div style=\"margin: auto;\"><div>";

$insert.=$sty10;

$insert.="<div class=\"Jea RDc hjj mQ8 zI7 iyn Hsu\"><div class=\"zI7 iyn Hsu\" style=\"max-width: 800px; width: 100%;\"><h2 class=\"lH1 dyH iFc SMy kON pBj IZT\">
<div>".$fn2."'s best boards</div></h2></div></div><div data-test-id=\"profileboards\" class=\"userBoards\"><div class=\"Grid\"><div class=\"GridItems centeredWithinWrapper padItems mb4\">";

//loop
$json2=substr($json,strpos($json,"\"data\":[")+8);
$json2=substr($json2,0,strpos($json2,"</script><script "));
$str="https://www.pinterest.com/resource/UnauthProfilePinFeedResource/get/?source_url=%2F".$fn."%2F&data=%7B%22options%22%3A%7B%22page_size%22%3A25%2C%22username%22%3A%22".$fn."%22%7D%7D&_=0";
$json3=templatehelp($str);
$key=substr($json2,0,strpos($json2,":"));
$f=0;
$dataurl=$d;
while ((strpos($key,"\"name\"")==1 || strpos($key,"\"id\"")==1 )&& $f<10 )
{$json=templatehelp($dataurl);$json2=substr($json,strpos($json,"\"data\":[")+8);$key=substr($json2,0,strpos($json2,":"));$f++;}

//echo $dataurl."<br>".$key.$f;exit;
$ex=explode($key,$json2);
$z=count($ex);


for($i=1;$i<$z;$i++) {
$ex[$i]=$key.$ex[$i];
$nam="";
//if (strpos($ex[$i],"\"annotations_with_links\"")>strpos($ex[$i],"\"name\"")) {
$nam=substr($ex[$i],strpos($ex[$i],"\"name\":")+8);
$nam=substr($nam,0,strpos($nam,"\","));
//}else {
//$nam=substr($ex[$i],strrpos($ex[$i],"\"name\""));
//$nam=substr($ex[$i],0,strpos($ex[$i],"\","));
//}

$url=substr($ex[$i],strpos($ex[$i],"\"url\":")+7);
$url=substr($url,0,strpos($url,"\","));
$pc=substr($ex[$i],strpos($ex[$i],"\"pin_count\":")+12);
$pc=substr($pc,0,strpos($pc,","));
if (strpos($pc,"}")>0) {$pc=substr($pc,0,strpos($pc,"}"));}
if ($pc>1000) {$l=strlen($pc);$pc=substr($pc,0,$l-3).",".substr($pc,$l-3);}
//echo $ex[$i];
$img=substr($ex[$i],strpos($ex[$i],"\"pin_thumbnail_urls\":")+22);
//echo $img;
$img=substr($img,0,strpos($img,"]"));
//echo $img;

$ex2=explode(",",$img);
$y=count($ex2);

if ($i%4==1) {$insert.="<div class=\"Jea kzZ zI7 iyn Hsu\">";}
$insert.="<div class=\"FNs Zr3 hA- zI7 iyn Hsu\">
<div class=\"item\">
<div class=\"Board Module boardCoverImage\">";
//<a class=\"boardLinkWrapper\" data-force-refresh=\"1\" href=\"".$url."\" rel=\"\">
$insert.="<div class=\"boardName hasBoardContext\">
<div class=\"BoardIcons Module pinCreate\"></div>
<div class=\"name\" style=\"padding: 2px 0px; font-size: 18px; font-weight: bold; color: rgb(85, 85, 85);\">".$nam."</div>
<div class=\"authorName\" style=\"overflow: hidden; text-overflow: ellipsis; width: 100%;\">
<div style=\"font-size: 14px; color: rgb(85, 85, 85); line-height: 1.14;\">".$fn2."
<span><span> • </span> <span>
<strong>".$pc."</strong> Pins</span>
</span></div></div></div>
<div class=\"boardImagesWrapper\">
<div class=\"boardCoverWrapper\">
<ul class=\"boardThumbs\">";

for($j=0;$j<($y>4?4:$y);$j++) {
$insert.="<li><span class=\"hoverMask\" style=\"z-index: 0;\"></span><img class=\"thumb\" alt=\"\" src=".$ex2[$j]."></li>";
if ($j==1) {$insert.="<li><span class=\"hoverMask\" style=\"z-index: 0;\"></span></li></ul></div><ul class=\"boardThumbs\">";}
}
//endloop
$imu=substr($ex[$i],strpos($ex[$i],"\"image_medium_url\":")+20);
$imu=substr($imu,0,strpos($imu,"\","));

$insert.="</ul><img alt=\"More from ".$fn2."\" src=\"".$imu."\" style=\"border: 2px solid rgb(255, 255, 255); border-radius: 50%; position: absolute; bottom: 50px; left: 8px; width: 40px; height: 40px;\"></div></a>
</div></div></div>";
}
$insert.="</div></div></div></div></div></div><div class=\"gridCentered\"><div class=\"Jea mQ8 zI7 iyn Hsu\"><div class=\"K5L zI7 iyn Hsu\" style=\"max-width: 800px; width: 100%;\"><h2 class=\"lH1 dyH iFc SMy kON pBj IZT\"><div>More ideas from ".$fn2."</div></h2></div></div></div><section class=\"gridCentered\" data-test-id=\"pinGrid\" style=\"margin-top: -20px;\">";
$insert.=$sty11;
$insert.="<div class=\"Grid__Container\" style=\"width: 736px; height: 7513px;\">";

//loop
//echo $json3;


$json3=substr($json3,strpos($json3,"\"data\":[")+8);
$key=substr($json3,0,strpos($json3,":"));
$ex=explode($key,$json3);
$z=count($ex);
$top0=0;$top1=0;$top2=0;$top3=0;

for($i=1;$i<$z;$i++) {
$i236="";$i30="";$desc="";$id="";
$ex[$i]=$key.$ex[$i];
//echo $ex[$i];
$agp=substr($ex[$i],strpos($ex[$i],"\"aggregated_pin_data\":")+23);
//echo $agp;
$id=substr($agp,strpos($agp,"\"id\":")+6);
//echo $id;
$id=substr($id,0,strpos($id,"\","));
if (strpos($id,"\"}")>0) {$id=substr($id,0,strpos($id,"\"}"));}

//echo $id;
//exit;
if (strpos($ex[$i],"\"description_html\":")>0) {
$desc=substr($ex[$i],strpos($ex[$i],"\"description_html\":")+20);
//echo $desc."<br>";
$desc=substr($desc,0,strpos($desc,"\","));
//echo $desc;exit;
}
$i236=substr($ex[$i],strpos($ex[$i],"\"236x\":")+7);
$ih3=substr($i236,strpos($i236,"height")+8);
//echo $ih3."<Br><br>";
$ih3=substr($ih3,0,strpos($ih3,","));
//echo $ih3."<Br><br>".$i236;
$i236=substr($i236,strpos($i236,"\"url\":")+7);
$i236=substr($i236,0,strpos($i236,"\"}"));
$i30=substr($ex[$i],strpos($ex[$i],"\"image_small_url\":")+19);
$i30=substr($i30,0,strpos($i30,"\","));
if (strpos($i30,"\"}")>0) {$i30=substr($i30,0,strpos($i30,"\"}"));}
//echo $ex[$i]."<br><br>";
$links="";
if (strpos($ex[$i],",\"annotations_with_links\":{")!==FALSE) {
//echo "test1<br><br>";
$links=substr($ex[$i],strpos($ex[$i],",\"annotations_with_links\":{")+27);
//echo $links."<br><br>";

$links=substr($links,0,strpos($links,"}}"));}
else if (strpos($ex[$i],"{\"annotations_with_links\":{")!==FALSE) {
//echo "test2<br><br>";

$links=substr($ex[$i],strpos($ex[$i],"{\"annotations_with_links\":{")+27);
$links=substr($links,0,strpos($links,"}}"));}
else if (strpos($ex[$i],"\"annotations_with_links\":")!==FALSE){
//echo "test3<br><br>";

$links=substr($ex[$i],strpos($$ex[$i],",\"annotations_with_links\":")+27);
$links=substr($links,0,strpos($links,"}}"));}

$fn=substr($ex[$i],strpos($ex[$i],",\"username\":\"")+13);
$fn=substr($fn,0,strpos($fn,",")-1);
$fn2=substr($ex[$i],strpos($ex[$i],",\"full_name\":\"")+14);
$fn2=substr($fn2,0,strpos($fn2,",")-1);
$board=substr($json,strpos($json,"\"board\":{")+0);
$url=substr($board,strpos($board,"\"url\":")+7);
$url=substr($url,0,strpos($url,"\","));
if (strpos($ex[$i],"\"annotations_with_links\"")>strpos($ex[$i],"\"name\"")) {
//echo "test1";
$nam=substr($ex[$i],strpos($ex[$i],"\"name\"")+8);
$nam=substr($nam,0,strpos($nam,"\","));
}
else {
//echo "test2";
$nam=substr($ex[$i],strrpos($ex[$i],"\"name\"")+8);
$nam=substr($nam,0,strpos($nam,"\","));
}
if (strpos($nam,"\"}")>0) {$nam=substr($nam,0,strpos($nam,"\"}"));}
//echo $nam;
//exit;

$jump=100;
$left=0;$tpo=0;
//if (strlen($text)>70) {$jump+=16;}
if ($top0<=$top1 && $top0<=$top2 && $top0<=$top3) {$left=0;$top=$top0;$top0+=$ih3+$jump;}
else if ($top1<=$top2 && $top1<=$top3) {$left=250;$top=$top1;$top1+=$ih3+$jump;}
else if ($top2<=$top3) {$left=500;$top=$top2;$top2+=$ih3+$jump;}
else {$left=750;$top=$top3;$top3+=$ih3+$jump;}

$insert.="<div><div class=\"Grid__Item\" style=\"top: ".$top."px; left: ".$left."px;\">
<div class=\"PinGridInner__brioPin GrowthUnauthPin_brioPin\" data-test-id=\"pin\" data-test-pin-id=\"".$id."\" role=\"button\" tabindex=\"0\" style=\"width: 236px;\">
<div class=\"GrowthUnauthPinImage\">
<a href=\"/pin/".$id."/\" target=\"_self\" title=\"Redirect Notice\" style=\"background: rgb(201, 180, 153);\">
<img alt=\"".strip_tags($desc)."\" class=\"GrowthUnauthPinImage__Image\" src=\"".$i236."\" style=\"height: ".$ih3."px;\"></a></div>
<figcaption>
<h3 class=\"PinDescription__desc PinDescription__1LineDesc\" data-test-id=\"desc\">Redirect Notice</h3></figcaption>
<div class=\"vaseCarousel_vasetags_container\" data-test-id=\"vasetags\" style=\"height: 30px;\">
<div class=\"vaseCarousel_vasetags_wrapper\">";
//loop2
$ex2=explode("{",$links);
$y=count($ex2);
//echo $links."<br>";
for ($j=1;$j<$y;$j++) {
//echo $ex2[$j];exit;
$p1=substr($ex2[$j],strpos($ex2[$j],"\"url\":\"")+7);
$p1=substr($p1,0,strpos($p1,"\","));
$p2=substr($ex2[$j],strpos($ex2[$j],"\"name\":\"")+8);
$p2=substr($p2,0,strpos($p2,"}")-1);


$insert.="<a href=\"".$p1."\" class=\"vaseCarousel_vaseTagLink\" target=\"_self\">".$p2."</a>";
}
//endloop2

$insert.="<a href=\"\" class=\"VaseCarousel__button VaseCarousel__buttonRight\">
<svg class=\"Hn_ gUZ B9u\" height=\"8\" width=\"8\" viewBox=\"0 0 24 24\" aria-label=\"Forward\" role=\"img\">
<path d=\"M6.72 24c.57 0 1.14-.22 1.57-.66L19.5 12 8.29.66c-.86-.88-2.27-.88-3.14 0-.87.88-.87 2.3 0 3.18L13.21 12l-8.06 8.16c-.87.88-.87 2.3 0 3.18.43.44 1 .66 1.57.66\"></path></svg></a></div></div>
<div class=\"pinDescription_pinnerBoardAttribution\">
<a class=\"underlineLink\" href=\"/".$fn."/\" rel=\"\">
<div class=\"pinDescription_pinnerProfileImage\" style=\"background-image: url(&quot;".$i30."&quot;); background-size: cover;\"></div></a>
<div class=\"pinDescription_attribute_container\">
<a class=\"underlineLink pinDescription_text\" href=\"/".$fn."/\" rel=\"\" style=\"font-weight: bolder;\">".$fn2."</a>
<a class=\"pinDescription_text pinDescription_text_board underlineLink\" href=\"".$url."/\" rel=\"\">".$nam."</a></div></div></div></div></div>";

//endloop
}

$insert.="<header data-test-id=\"unauthHeader\" id=\"HeaderContent\" style=\"top: 0px; background-color: rgb(255, 255, 255); border-bottom: 1px solid rgb(204, 204, 204); width: 100%; z-index: 675; position: fixed;\"><div style=\"height: 64px; display: flex; align-items: center; margin: 0px 16px;\"><div style=\"order: 1;\"><div style=\"display: inline-flex;\"><div class=\"UnauthHeader__rightContentContainer\" style=\"position: relative;\"><div data-test-id=\"signupButton\" class=\"zI7 iyn Hsu\"><button aria-label=\"\" class=\"red headerSignupButton active\" type=\"button\" style=\"border: 0px; height: 40px; display: inline-block; border-radius: 4px; -webkit-font-smoothing: antialiased; padding: 0px; font-size: 14px; font-weight: bold; outline: none; box-shadow: none; cursor: pointer; margin-top: 0px; vertical-align: middle; text-align: center; background-color: rgb(230, 0, 35); color: rgb(255, 255, 255); line-height: 36px; width: 128px; margin-right: 8px;\">Sign up</button></div><div data-test-id=\"loginButton\" class=\"Jea XiG gjz mQ8 zI7 iyn Hsu\"><button aria-label=\"\" class=\"lightGrey headerLoginButton active\" type=\"button\" style=\"border: 0px; height: 40px; display: inline-block; border-radius: 4px; -webkit-font-smoothing: antialiased; padding: 0px; font-size: 14px; font-weight: bold; outline: none; box-shadow: none; cursor: pointer; margin-top: 0px; vertical-align: middle; text-align: center; background-color: rgb(235, 235, 235); color: rgb(68, 68, 68); width: 128px;\">Log in</button></div><div><div class=\"Jea hDW mQ8 n9m zI7 iyn Hsu\"><button aria-label=\"Setting button\" class=\"rYa kVc adn yQo qrs BG7\" type=\"button\"><div class=\"x8f INd _O1 gjz mQ8 OGJ YbY\" style=\"height: 32px; width: 32px;\"><div class=\"INd zI7 iyn Hsu\"><svg class=\"gUZ pBj U9O kVc\" height=\"16\" width=\"16\" viewBox=\"0 0 24 24\" aria-hidden=\"true\" aria-label=\"\" role=\"img\"><path d=\"M12 9c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3M3 9c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm18 0c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3z\"></path></svg></div></div></button></div></div></div></div></div><div style=\"display: flex; flex: 1 1 0%; align-items: center;\"><a class=\"Wk9 xQ4 WMU iyn ljY kVc\" href=\"/\"><span style=\"cursor: pointer; display: block; float: left; height: 28px; width: 28px; vertical-align: middle;\"><svg height=\"28\" viewBox=\"3 3 70 70\" width=\"28\" style=\"display: block;\"><path d=\"M27.5 71c3.3 1 6.7 1.6 10.3 1.6C57 72.6 72.6 57 72.6 37.8 72.6 18.6 57 3 37.8 3 18.6 3 3 18.6 3 37.8c0 14.8 9.3 27.5 22.4 32.5-.3-2.7-.6-7.2 0-10.3l4-17.2s-1-2-1-5.2c0-4.8 3-8.4 6.4-8.4 3 0 4.4 2.2 4.4 5 0 3-2 7.3-3 11.4C35.6 49 38 52 41.5 52c6.2 0 11-6.6 11-16 0-8.3-6-14-14.6-14-9.8 0-15.6 7.3-15.6 15 0 3 1 6 2.6 8 .3.2.3.5.2 1l-1 3.8c0 .6-.4.8-1 .4-4.4-2-7-8.3-7-13.4 0-11 7.8-21 22.8-21 12 0 21.3 8.6 21.3 20 0 12-7.4 21.6-18 21.6-3.4 0-6.7-1.8-7.8-4L32 61.7c-.8 3-3 7-4.5 9.4z\" fill=\"#e60023\" fill-rule=\"evenodd\"></path></svg></span></a><a href=\"/\" role=\"button\" style=\"text-decoration: none;\"><span class=\"UnauthHeader__discoverText\" style=\"color: rgb(67, 67, 67); font-size: 18px; font-weight: bold; margin-left: 14px; -webkit-font-smoothing: antialiased;\">Pinterest</span></a><div style=\"flex: 1 1 0%;\">";
$insert.=$sty12;
$insert.="<div class=\"OpenSearchForm\" style=\"box-sizing: border-box; display: block; float: none; height: auto; margin-left: 16px; margin-right: 16px; position: relative; text-align: left; width: auto;\"><form name=\"search\"><div class=\"typeaheadField guided\" style=\"display: block; float: none; position: relative; width: 100%;\"><div class=\"tokenizedInputWrapper\" role=\"search\" style=\"background-color: rgb(239, 239, 239); border-radius: 4px; box-sizing: border-box; height: 40px; position: relative; display: flex; align-items: center;\"><em></em><div style=\"margin-left: 16px;\"><svg class=\"gUZ B9u U9O kVc\" height=\"20\" width=\"20\" viewBox=\"0 0 24 24\" aria-label=\"search\" role=\"img\"><path d=\"M10 16c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6m13.12 2.88l-4.26-4.26A9.842 9.842 0 0 0 20 10c0-5.52-4.48-10-10-10S0 4.48 0 10s4.48 10 10 10c1.67 0 3.24-.41 4.62-1.14l4.26 4.26a3 3 0 0 0 4.24 0 3 3 0 0 0 0-4.24\"></path></svg></div><div class=\"tokenizedInput guided typeaheadWithTitles\" style=\"margin-left: 11px; overflow: hidden; flex: 1 1 0%; display: flex; align-items: center;\"><div class=\"scrollWrapper\" style=\"overflow: hidden; flex: 1 1 0%;\"><ul class=\"tokensWrapper\" style=\"cursor: text; float: left; min-height: 1px; white-space: nowrap; width: 100%; left: 0px; overflow: visible; position: relative; transition-duration: 0.25s; transition-property: left;\"><li class=\"tokenizedItem inputToken\" style=\"color: rgb(0, 0, 0); display: inline-block; margin: 0px; overflow: hidden; width: 100%; white-space: nowrap; vertical-align: middle;\"><label><div class=\"NVN zI7 iyn Hsu\">Search</div><input autocomplete=\"off\" class=\"OpenSearchBoxInput\" name=\"q\" placeholder=\"Search for easy dinners, fashion, etc.\" type=\"text\" value=\"\" style=\"background: transparent; border: 0px; border-radius: 3px; box-shadow: none; box-sizing: border-box; font-size: 16px; font-weight: 600; height: 40px; line-height: 20px; overflow: hidden; width: 100%;\"></label></li></ul></div></div></div><div><div class=\"OpenTypeahead guided typeaheadWithTitles\" style=\"margin: 41px 0px 0px; position: absolute; border-radius: 0px 0px 6px 6px; top: 0px;\"><ul class=\"results\"></ul></div></div></div></form></div></div></div></div></header><div><div data-test-id=\"giftWrap\" role=\"dialog\" style=\"background: rgba(0, 0, 0, 0.65); transition: height 0.5s cubic-bezier(0.26, 0.87, 0.74, 0.93) 0s; backface-visibility: hidden; bottom: 0px; color: rgb(255, 255, 255); height: 0px; position: fixed; width: 100%;\"><div data-test-id=\"quarterBanner\" class=\"gridCentered\" style=\"transition: opacity 0.5s linear 0s; pointer-events: none; visibility: hidden; opacity: 0; height: 0px;\"><div class=\"Jea b8T gjz zI7 iyn Hsu\" style=\"height: 64px; width: 100%;\"><div style=\"color: rgb(255, 255, 255); display: inline-block; font-size: 24px; font-weight: 500; vertical-align: middle; font-style: normal; font-stretch: normal; line-height: normal; text-align: left;\">Explore more ideas with a Pinterest account</div><div class=\"Jea b8T hA- wYR zI7 iyn Hsu\"><button aria-label=\"\" class=\"white active\" type=\"button\" style=\"border: 0px; height: 44px; display: inline-block; border-radius: 4px; -webkit-font-smoothing: antialiased; padding: 0px 14px; font-size: 16px; font-weight: bold; outline: none; box-shadow: none; cursor: pointer; margin-top: 0px; vertical-align: middle; text-align: center; background-color: rgb(255, 255, 255); color: rgb(68, 68, 68); line-height: 36px; min-width: 128px; margin-right: 5px; white-space: nowrap;\">Sign up</button><button aria-label=\"\" class=\"darkGrey active\" type=\"button\" style=\"border: 1px solid rgb(255, 255, 255); height: 44px; display: inline-block; border-radius: 4px; -webkit-font-smoothing: antialiased; padding: 0px 14px; font-size: 16px; font-weight: bold; outline: none; box-shadow: none; cursor: pointer; margin-top: 0px; vertical-align: middle; text-align: center; background-color: transparent; color: rgb(255, 255, 255); line-height: 36px; min-width: 128px; margin-right: 5px;\">Log in</button></div></div></div><div data-test-id=\"fullBanner\" role=\"dialog\" style=\"transition: opacity 0.5s linear 0s; pointer-events: none; visibility: hidden; opacity: 0; height: 0px;\"><div role=\"dialog\" style=\"pointer-events: none; visibility: hidden; opacity: 0;\"><div data-test-id=\"signup\" style=\"background-color: rgb(255, 255, 255); border-radius: 8px; position: relative; text-align: center; width: 484px; margin: auto; min-height: 450px; box-shadow: rgba(0, 0, 0, 0.45) 0px 2px 10px;\"><div style=\"min-height: 400px; padding: 20px 10px 24px;\"><div style=\"display: block; height: 45px; margin: 5px auto 8px; width: 45px;\"><svg height=\"48\" viewBox=\"-3 -3 82 82\" width=\"48\" style=\"display: block;\"><circle cx=\"38\" cy=\"38\" fill=\"white\" r=\"40\"></circle><path d=\"M27.5 71c3.3 1 6.7 1.6 10.3 1.6C57 72.6 72.6 57 72.6 37.8 72.6 18.6 57 3 37.8 3 18.6 3 3 18.6 3 37.8c0 14.8 9.3 27.5 22.4 32.5-.3-2.7-.6-7.2 0-10.3l4-17.2s-1-2-1-5.2c0-4.8 3-8.4 6.4-8.4 3 0 4.4 2.2 4.4 5 0 3-2 7.3-3 11.4C35.6 49 38 52 41.5 52c6.2 0 11-6.6 11-16 0-8.3-6-14-14.6-14-9.8 0-15.6 7.3-15.6 15 0 3 1 6 2.6 8 .3.2.3.5.2 1l-1 3.8c0 .6-.4.8-1 .4-4.4-2-7-8.3-7-13.4 0-11 7.8-21 22.8-21 12 0 21.3 8.6 21.3 20 0 12-7.4 21.6-18 21.6-3.4 0-6.7-1.8-7.8-4L32 61.7c-.8 3-3 7-4.5 9.4z\" fill=\"#e60023\" fill-rule=\"evenodd\"></path></svg></div><div style=\"margin: 0px auto 18px; width: 400px;\"></div><div style=\"margin: 0px auto 18px; width: 270px;\"><h3 style=\"text-align: center; color: rgb(51, 51, 51); font-size: 16px; font-weight: normal; margin: -15px 0px 32px;\">Sign up to see more</h3></div><div data-test-id=\"signup\" style=\"margin: 0px auto; position: relative; text-align: center;\"><div style=\"margin: 0px auto; width: 268px;\"><div><div data-test-id=\"emailSignUpButton\"><button class=\"RCK Hsu mix Vxj aZc GmH adn Il7 Jrn hNT iyn BG7 gn8 L4E kVc\" type=\"button\"><div class=\"tBJ dyH iFc SMy yTZ erh tg7 mWe\">Continue with email</div></button></div><div style=\"margin-top: 10px;\"><div style=\"position: relative;\"><div><button aria-label=\"\" class=\"FacebookConnectButton active\" type=\"button\" style=\"border: 0px; height: 40px; display: block; border-radius: 8px; -webkit-font-smoothing: antialiased; padding: 0px 0px 0px 8px; font-size: 15px; font-weight: normal; outline: none; box-shadow: none; cursor: pointer; margin-top: 0px; vertical-align: middle; text-align: left; background-clip: padding-box; background-color: rgb(24, 119, 242); position: absolute; transition: opacity 0.2s linear 0s; width: 100%;\"><svg class=\"gUZ erh\" height=\"24\" width=\"24\" viewBox=\"0 0 24 24\" aria-label=\"facebook\" role=\"img\"><path d=\"M17.75 3.984l-2.312.001c-1.811 0-2.163.842-2.163 2.077v2.724h4.323l-.563 4.267h-3.76V24H8.769V13.053H5V8.786h3.769V5.64C8.769 1.988 11.05 0 14.383 0c1.596 0 2.967.116 3.367.168v3.816z\"></path></svg><span style=\"color: rgb(255, 255, 255); display: inline-block; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen-Sans, Ubuntu, Cantarell, &quot;Fira Sans&quot;, &quot;Droid Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;ヒラギノ角ゴ Pro W3&quot;, &quot;Hiragino Kaku Gothic Pro&quot;, メイリオ, Meiryo, &quot;ＭＳ Ｐゴシック&quot;, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;; font-size: 15px; font-weight: bold; letter-spacing: normal; line-height: 16px; padding-left: 4px; padding-top: 1px; text-align: center; vertical-align: text-bottom; -webkit-font-smoothing: auto; white-space: normal; width: 88%;\">Continue with Facebook</span></button><div style=\"height: 40px; text-align: left;\"><div class=\"fb-login-button fb_iframe_widget\" data-scope=\"public_profile,email,user_likes,user_birthday,user_friends\" onlogin=\"checkLoginState\" data-button-type=\"continue_with\" data-use-continue-as=\"true\" data-width=\"268px\" data-size=\"large\" login_text=\"\" fb-xfbml-state=\"rendered\" fb-iframe-plugin-query=\"app_id=274266067164&amp;button_type=continue_with&amp;container_width=268&amp;locale=en_US&amp;scope=public_profile%2Cemail%2Cuser_likes%2Cuser_birthday%2Cuser_friends&amp;sdk=joey&amp;size=large&amp;use_continue_as=true&amp;width=268px\"><span style=\"vertical-align: bottom; width: 268px; height: 40px;\"><iframe name=\"f134b8a087d4c54\" height=\"1000px\" title=\"fb:login_button Facebook Social Plugin\" frameborder=\"0\" allowtransparency=\"true\" allowfullscreen=\"true\" scrolling=\"no\" allow=\"encrypted-media\" src=\"https://www.facebook.com/v2.2/plugins/login_button.php?app_id=274266067164&amp;button_type=continue_with&amp;channel=https%3A%2F%2Fstaticxx.facebook.com%2Fconnect%2Fxd_arbiter.php%3Fversion%3D45%23cb%3Df38ee4d3753b47c%26domain%3Dwww.pinterest.com%26origin%3Dhttps%253A%252F%252Fwww.pinterest.com%252Ff2d29742f2238f%26relation%3Dparent.parent&amp;container_width=268&amp;locale=en_US&amp;scope=public_profile%2Cemail%2Cuser_likes%2Cuser_birthday%2Cuser_friends&amp;sdk=joey&amp;size=large&amp;use_continue_as=true&amp;width=268px\" style=\"border: none; visibility: visible; width: 268px; height: 40px;\" class=\"\"></iframe></span></div></div></div></div><div style=\"height: 10px;\"></div><div><button aria-label=\"\" class=\"GoogleConnectButton active\" id=\"googleConnectButton\" type=\"button\" style=\"border: 0px; height: 40px; display: block; border-radius: 4px; -webkit-font-smoothing: antialiased; padding: 1px; font-size: 15px; font-weight: bold; outline: none; box-shadow: none; cursor: pointer; margin-top: 0px; vertical-align: middle; text-align: left; margin-right: 0px; background-clip: padding-box; transition: opacity 0.2s linear 0s; position: relative; width: 100%; background-color: rgb(239, 239, 239);\"><div style=\"border-radius: 2px; background-color: transparent; display: inline-block; height: 24px; margin-left: 12px; text-align: center; width: 24px;\"><div style=\"position: relative; margin: 2px auto auto;\"><svg height=\"20\" viewBox=\"0 0 512 512\" width=\"20\" xmlns=\"http://www.w3.org/2000/svg\"><g fill=\"none\" fill-rule=\"evenodd\"><path d=\"M482.56 261.36c0-16.73-1.5-32.83-4.29-48.27H256v91.29h127.01c-5.47 29.5-22.1 54.49-47.09 71.23v59.21h76.27c44.63-41.09 70.37-101.59 70.37-173.46z\" fill=\"#4285f4\"></path><path d=\"M256 492c63.72 0 117.14-21.13 156.19-57.18l-76.27-59.21c-21.13 14.16-48.17 22.53-79.92 22.53-61.47 0-113.49-41.51-132.05-97.3H45.1v61.15c38.83 77.13 118.64 130.01 210.9 130.01z\" fill=\"#34a853\"></path><path d=\"M123.95 300.84c-4.72-14.16-7.4-29.29-7.4-44.84s2.68-30.68 7.4-44.84V150.01H45.1C29.12 181.87 20 217.92 20 256c0 38.08 9.12 74.13 25.1 105.99l78.85-61.15z\" fill=\"#fbbc05\"></path><path d=\"M256 113.86c34.65 0 65.76 11.91 90.22 35.29l67.69-67.69C373.03 43.39 319.61 20 256 20c-92.25 0-172.07 52.89-210.9 130.01l78.85 61.15c18.56-55.78 70.59-97.3 132.05-97.3z\" fill=\"#ea4335\"></path><path d=\"M20 20h472v472H20V20z\"></path></g></svg></div></div><span style=\"color: rgb(51, 51, 51); display: inline-block; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen-Sans, Ubuntu, Cantarell, &quot;Fira Sans&quot;, &quot;Droid Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;ヒラギノ角ゴ Pro W3&quot;, &quot;Hiragino Kaku Gothic Pro&quot;, メイリオ, Meiryo, &quot;ＭＳ Ｐゴシック&quot;, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;; position: absolute; text-align: center; top: 50%; font-size: 16px; transform: translateY(-50%) translateX(-37px); vertical-align: top; -webkit-font-smoothing: auto; width: 100%;\">Continue with Google</span></button></div></div></div><div style=\"margin-top: 16px;\"><span class=\"\" style=\"-webkit-font-smoothing: antialiased; font-size: 11px; font-weight: normal; text-align: center; transition: opacity 0.2s linear 0s; color: rgb(142, 142, 142); width: 224px;\"><span>By continuing, you agree to Pinterest's <a data-test-id=\"tos\" href=\"/_/_/about/terms-service/\" target=\"_blank\">Terms of Service</a>, <a data-test-id=\"privacy\" href=\"/_/_/about/privacy/\" target=\"_blank\">Privacy Policy</a></span></span></div><div><div style=\"border-bottom: 1px solid rgb(239, 239, 239); margin: 20px 0px 15px -68px; width: 404px;\"></div><div><div style=\"margin: 0px auto 5px; width: fit-content; align-items: baseline;\"><div class=\"zI7 iyn Hsu\"><a style=\"color: rgb(51, 51, 51); cursor: pointer; margin-left: 5px;\">Already a member? Log in</a></div></div></div></div></div></div></div></div></div></div></div></div><button aria-label=\"\" class=\"noButtonStyles active\" type=\"button\" style=\"background: none; border: none; padding: 0px; text-align: left;\"><div style=\"background-color: rgb(255, 255, 255); border-radius: 4px; box-shadow: rgba(0, 0, 0, 0.1) 0px 2px 0px 0px, rgba(0, 0, 0, 0.04) 0px 0px 0px 0.5px; color: rgb(51, 51, 51); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen-Sans, Ubuntu, Cantarell, &quot;Fira Sans&quot;, &quot;Droid Sans&quot;, &quot;Helvetica Neue Bold&quot;, Helvetica, &quot;ヒラギノ角ゴ Pro W3&quot;, &quot;Hiragino Kaku Gothic Pro&quot;, メイリオ, Meiryo, &quot;ＭＳ Ｐゴシック&quot;, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;; font-size: 12px; font-weight: normal; padding: 2px 5px 3px; position: fixed; right: 10px; bottom: 80px;\">Privacy</div></button></div></div></div><div style=\"bottom: 14px; left: 50%; margin-left: -183px; position: fixed; z-index: 1500;\"></div></div>";
//$pre=str_replace("<script","< ",$pre);
//$pre=str_replace("<link","< ",$pre);
$r=$pre.$insert.$post;
}



else if ($a==="pinterest.com" && $b==="/pin/") {
require("pinstyle.php");
$head=substr($c,0,strpos($c,"<head"));
$c=substr($c,strpos($c,"<head"));
$c=$head."<base href=\"https://www.pinterest.com\">".$c;
$pre=substr($c,0,strpos($c,"<div id=\"__PWS_ROOT__\" data-reactcontainer"));
$post=substr($c,strpos($c,"<div id=\"__PWS_ROOT__\" data-reactcontainer"));
$pre.=substr($post,0,strpos($post,"</div"));
$post=substr($post,strpos($post,"</div"));
$json=substr($post,strpos($post,"{"));
$json=substr($json,0,strrpos($json,"</script>"));
$post="</body></html>";

$fn=substr($json,strpos($json,",\"username\":\"")+13);
$fn=substr($fn,0,strpos($fn,",")-1);
$fn2=substr($json,strpos($json,",\"full_name\":\"")+14);
$fn2=substr($fn2,0,strpos($fn2,",")-1);

$path=substr($json,strpos($json,",\"slug\":\"")+9);
$path=substr($path,0,strpos($path,",")-1);
$id=substr($json,strpos($json,",\"id\":\"")+7);
$id=substr($id,0,strpos($id,",")-1);
$id2=substr($json,strpos($json,"\"id\":")+6);
$id2=substr($id2,strpos($id2,"\"id\":")+6);
$id2=substr($id2,0,strpos($id2,",")-1);
$path2=substr($json,strpos($json,"\"url\":\"/".$fn)+9);
$path2=substr($path2,0,strpos($path2,",")-1);
$board=substr($json,strpos($json,"\"board\":{")+0);
$name=substr($board,strpos($board,"\"name\":\"")+7);
$name=substr($name,0,strpos($name,"\","));
$fv=substr($json,strpos($json,"\"repin_count\":")+14);
$fv=substr($fv,0,strpos($fv,","));
if ($fv>1000) {$fv=((int)($fv/1000));$fv.="k";}

$links="";
if (strpos($json,",\"annotations_with_links\":{")!==FALSE) {
$links=substr($json,strpos($json,",\"annotations_with_links\":{")+27);
$links=substr($links,0,strpos($links,"}}"));
}
else if (strpos($json,"{\"annotations_with_links\":{")!==FALSE) {
$links=substr($json,strpos($json,"{\"annotations_with_links\":{")+27);
$links=substr($links,0,strpos($links,"}}"));
}
else {
$links=substr($json,strpos($json,",\"annotations_with_links\":")+27);
$links=substr($links,0,strpos($links,"}}"));
}




$insert="<div><div><div class=\"zI7 iyn Hsu\">";
$insert.=$sty5;
$insert.="<main data-test-id=\"unauthPinPage\">";
$insert.=$sty6;


$insert.="<div style=\"background-color: rgb(239, 239, 239); position: fixed; top: 0px; left: 0px; right: 0px; bottom: 0px; z-index: -1000;\"></div><div>
<div style=\"padding: 64px 0px 0px; width: 100%; height: 450px;\">
<div><div style=\"visibility: hidden;\">
<div style=\"background-color: rgba(85, 85, 85, 0.9); color: rgb(51, 51, 51); height: 100vh; left: 0px; position: fixed; top: 0px; -webkit-font-smoothing: antialiased; width: 100vw; z-index: 1;\"><section style=\"background-color: white; border-radius: 8px; left: 50%; position: absolute; top: 50%; transform: translate(-50%, calc(-50% - 32px)); padding-bottom: 10px; width: 414px;\"><div style=\"border-bottom: 1px solid rgb(239, 239, 239); display: flex; height: 60px; justify-content: flex-end; padding: 0px 24px;\">
<div role=\"button\" tabindex=\"0\" style=\"cursor: pointer; fill: rgb(142, 142, 142); margin-top: 20px;\">
<svg class=\"gUZ B9u\" height=\"18\" width=\"18\" viewBox=\"0 0 24 24\" aria-label=\"Cancel\" role=\"img\">
<path d=\"M15.18 12l7.16-7.16c.88-.88.88-2.3 0-3.18-.88-.88-2.3-.88-3.18 0L12 8.82 4.84 1.66c-.88-.88-2.3-.88-3.18 0-.88.88-.88 2.3 0 3.18L8.82 12l-7.16 7.16c-.88.88-.88 2.3 0 3.18.44.44 1.01.66 1.59.66.58 0 1.15-.22 1.59-.66L12 15.18l7.16 7.16c.44.44 1.01.66 1.59.66.58 0 1.15-.22 1.59-.66.88-.88.88-2.3 0-3.18L15.18 12z\"></path></svg></div>
<div style=\"font-size: 21px; font-weight: bold; left: 50%; margin-top: 20px; position: absolute; transform: translateX(-50%);\">More information</div></div>
<div style=\"max-height: calc(60vh - 60px); overflow-y: auto; padding: 0px 24px 24px;\">";

$insert.="<h2 style=\"font-size: 16px; margin-top: 15px;\">S&amp;W Victory Revolver Action</h2>
<div style=\"font-size: 16px; margin-top: 12px;\">Find this Pin and more on ";

$insert.="<a href=\"/".$path2."\" style=\"font-weight: bold; text-decoration: none;\">".$name."</a> by 
<a href=\"/".$fn."/\" style=\"font-weight: bold; text-decoration: none;\">".$fn2."</a>.</div>
<div style=\"font-size: 18px; font-weight: bold; margin-top: 16px;\">Tags</div>
<div style=\"display: flex; flex-wrap: wrap;\">";
$ex=explode("{",$links);
$z=count($ex);
$cheat="";
for ($i=1;$i<$z;$i++) {
$p1=substr($ex[$i],strpos($ex[$i],"\"url\":\"")+7);
$p1=substr($p1,0,strpos($p1,"\","));
$p2=substr($ex[$i],strpos($ex[$i],"\"name\":\"")+8);
$p2=substr($p2,0,strpos($p2,"\","));
if ($i>1) {$cheat.=",";}
$cheat.=" ".$p2;
$insert.="<div class=\"FNs Hb9 gpV hA- hDW mix wYR zI7 iyn Hsu\"><a href=\"".$p1."/\" rel=\"tag\"><div class=\"".$p2."\">".$p2."</div></a></div>";
}
$insert.="</div>
<div style=\"font-size: 18px; font-weight: bold; margin-top: 16px;\">What others are saying</div>";

$vd=substr($json,strpos($json,"\"visual_descriptions\":[")+24);
$vd=substr($vd,0,strpos($vd,"\"]"));
$ex=explode("\",\"",$vd);
$z=count($ex);
for ($i=0;$i<$z;$i++) {$insert.="<h4 data-test-id=\"vasedesc\" style=\"font-size: 16px; font-weight: normal; margin-top: 12px;\">".$ex[$i]."</h4>";}
$insert.="</div></section></div></div>
<div data-test-id=\"pin\" style=\"display: flex; flex-direction: column;\"><div data-test-id=\"pinHeader\" style=\"place-content: center; background-color: white; flex-direction: row; display: flex; flex: 1 1 auto; padding: 24px 12px; box-sizing: inherit; height: 392px; min-width: 1026px;\"><div data-test-id=\"closeupPin\" class=\"mix zI7 iyn Hsu\" style=\"min-width: 262px;\"><div role=\"button\" tabindex=\"0\" style=\"cursor: pointer;\"><div class=\"GrowthUnauthPinImage\"><div style=\"cursor: default;\"><div class=\"Pj7 sLG XiG sjM gL3 m1e\" style=\"width: 262px; height: 392px;\"><div style=\"margin-top: 0px; margin-left: -66.9798px;\"><div class=\"Pj7 sLG XiG sjM gL3 m1e\" style=\"width: 395.96px; height: 392px;\">";

$cun=substr($json,strpos($json,"\"closeup_user_note\":\"")+21);
$cun=substr($cun,0,strpos($cun,"\",\""));

$image=substr($json,strpos($json,"\"images\":{")+10);
$image=substr($image,0,strpos($image,"}}"));
$orig=substr($image,strpos($image,"\"orig\":")+7);
//echo "<br>orig".$orig;
$orig=substr($orig,strpos($orig,"\"url\":\"")+7);
$orig=substr($orig,0,strlen($orig)-1);

//echo "<br>orig".$orig;
//$orig=substr($orig,0,strpos($orig,"}"));


$insert.="<img alt=\"".$cun.$cheat."\" class=\"GrowthUnauthPinImage__Image\" src=\"".$orig."\"></div></div></div></div></div></div></div><section style=\"margin-left: 20px;\"><div data-test-id=\"closeupDescription\" class=\"Jea XiG bkI jzS zI7 iyn Hsu\" style=\"height: 392px; width: 330px;\"><div class=\"Jea XiG jzS ujU zI7 iyn Hsu\"><div class=\"F6l Jea k1A zI7 iyn Hsu\"><div class=\"RDc xvE zI7 iyn Hsu\"><a href=\"#\" rel=\"nofollow\" style=\"cursor: pointer; text-decoration: none;\"><div class=\"tBJ dyH iFc SMy MF7 pBj DrD IZT swG\">Saved from  ";

$dom=substr($json,strpos($json,"\"domain\":")+10);
$dom=substr($dom,0,strpos($dom,"\","));
$title=substr($json,strpos($json,"\"title\":")+9);
$title=substr($title,0,strpos($title,"\","));
//echo "<br>dom".$dom;
$insert.="
<span class=\"tBJ dyH iFc SMy MF7 pBj DrD IZT mWe\">".$dom."</span></div></a></div></div>
<div class=\"Jea b8T hs0 zI7 iyn Hsu\"><div class=\"Jea sLG zI7 iyn Hsu\">
<h1 class=\"lH1 dyH iFc SMy kON pBj IZT\">".$title."</h1></div></div><div class=\"Hvp Jea sLG zI7 iyn Hsu\">";

$desc=substr($json,strpos($json,"\"rich_metadata\":")+15);
$desc=substr($desc,strpos($desc,"\"description\":")+15);
$desc=substr($desc,0,strpos($desc,"\","));

$insert.="<div class=\"tBJ dyH iFc SMy MF7 pBj DrD swG\">".$desc."</div></div><div class=\"Hvp zI7 iyn Hsu\">";

$insert.="<div class=\"vaseCarousel_vasetags_container\" data-test-id=\"vasetags\" style=\"height: 30px;\"><div class=\"vaseCarousel_vasetags_wrapper\">";


$ex=explode("{",$links);
$z=count($ex);
$cheat="";
for ($i=1;$i<$z;$i++) {
//echo "<br>forlinks".$ex[$i];
$p1=substr($ex[$i],strpos($ex[$i],"\"url\":\"")+7);
$p1=substr($p1,0,strpos($p1,"\","));
//echo "<br>p1".$p1;

$p2=substr($ex[$i],strpos($ex[$i],"\"name\":\"")+8);
$p2=substr($p2,0,strpos($p2,"}")-1);

if ($i>1) {$cheat.=",";}
$cheat.=" ".$p2;
$insert.="<a href=\"".$p1."/\" class=\"vaseCarousel_vaseTagLink\" target=\"_self\">".$p2."</a>";
}
//echo "<br>image".$image;
//echo "<br>orig".$orig;
//echo "<br>description".$desc;
//echo "<br>links".$links;
//echo "<br>cheat".$cheat;
//exit;

$insert.="<a href=\"\" class=\"VaseCarousel__button VaseCarousel__buttonRight\">
<svg class=\"Hn_ gUZ B9u\" height=\"8\" width=\"8\" viewBox=\"0 0 24 24\" aria-label=\"Forward\" role=\"img\">
<path d=\"M6.72 24c.57 0 1.14-.22 1.57-.66L19.5 12 8.29.66c-.86-.88-2.27-.88-3.14 0-.87.88-.87 2.3 0 3.18L13.21 12l-8.06 8.16c-.87.88-.87 2.3 0 3.18.43.44 1 .66 1.57.66\"></path></svg></a></div></div></div><div class=\"hDW zI7 iyn Hsu\">
<button style=\"background-color: transparent; border: 0px; color: rgb(142, 142, 142); font-size: 12px; font-weight: bold; height: 13px; letter-spacing: -0.3px; outline: none; padding: 0px; -webkit-font-smoothing: antialiased;\">More information</button></div></div>
<div class=\"Jea hDW zI7 iyn Hsu\">
<div class=\"Jea b8T gjz hs0 ujU zI7 iyn Hsu\">
<a href=\"/".$fn."/\" target=\"_blank\">
<div class=\"Jea hs0 ujU zI7 iyn Hsu\">
<div class=\"zI7 iyn Hsu\" style=\"min-width: 50px;\">
<div style=\"border: 0px; border-radius: 50%; cursor: pointer; display: block; height: 44px; margin-right: 8px; perspective: 1px; text-align: center; width: 44px; background-image: url(&quot;https://i.pinimg.com/75x75_RS/3b/94/28/3b942806a15b79c7bde733c943372a03.jpg&quot;); background-size: cover;\"></div></div>
<div class=\"Jea jzS mQ8 zI7 iyn Hsu\" style=\"min-width: 0px; max-width: 230px;\"><div class=\"tBJ dyH iFc SMy MF7 pBj DrD IZT swG\">Saved by</div>
<div class=\"tBJ dyH iFc SMy MF7 pBj DrD IZT mWe z-6\" title=\"".$fn2."\">".$fn2."</div></div></div></a>
<div class=\"Jea jx- zI7 iyn Hsu\"><div class=\"Jea gjz zI7 iyn Hsu\">
<svg class=\"gUZ B9u U9O kVc\" height=\"16\" width=\"16\" viewBox=\"0 0 24 24\" aria-label=\"Pin\" role=\"img\">
<path d=\"M18 13.5c0-2.22-1.21-4.15-3-5.19V2.45A2.5 2.5 0 0 0 17 0H7a2.5 2.5 0 0 0 2 2.45v5.86c-1.79 1.04-3 2.97-3 5.19h5v8.46L12 24l1-2.04V13.5h5z\"></path></svg>
<span class=\"tBJ dyH iFc SMy MF7 pBj DrD IZT swG\">".$fv."</span></div></div></div></div></div></section>
<section style=\"margin-left: 40px;\">
<div data-test-id=\"closeupSimilarPins\" class=\"Jea PKX jzS zI7 iyn Hsu\">
<div class=\"hDW zI7 iyn Hsu\">
<h2 style=\"margin-top: 4px;\">Similar ideas</h2></div>";
$json2=substr($json,strpos($json,"\"data\":[")+8);
$json2=substr($json2,0,strpos($json2,"</script><script "));
$json3=substr($json2,strpos($json2,"</script><script "));

$key=substr($json2,0,strpos($json2,":"));
$ex=explode($key,$json2);
$z=count($ex);
for($i=1;$i<7;$i++) {
$cheat="";
$board=substr($ex[$i],strpos($ex[$i],"\"board\":{")+0);
$name=substr($board,strpos($board,"\"name\":\"")+7);
$name=substr($name,0,strpos($name,"\","));
$url=substr($board,strpos($board,"\"url\":\"")+6);
$url=substr($url,0,strpos($url,"\","));
$id=substr($board,strpos($board,"\"id\":\"")+6);
$id=substr($id,0,strpos($id,"\","));
if (strpos($id,"}")>0) {$id=substr($id,0,strpos($id,"}"));}
//echo "<br>id:".$id;

//exit;
$desc=substr($ex[$i],strpos($ex[$i],"\"description\":")+15);
if(substr($desc,0,4)==="ull,") {$desc="null";} else {$desc=substr($desc,0,strpos($desc,"\","));}
//echo $desc;exit;

$fn3=substr($ex[$i],strpos($ex[$i],"\"full_name\":")+13);
$fn3=substr($fn3,0,strpos($fn3,"\","));

if ($i%3==1) {$insert.="<div data-test-id=\"closeupSimilarPinsRow\" class=\"Hvp zI7 iyn Hsu\"><div class=\"Jea zI7 iyn Hsu\">";}

$insert.="<div class=\"R1w zI7 iyn Hsu\" style=\"min-width: 100px;\">
<div class=\"zI7 iyn Hsu\">
<div style=\"visibility: hidden;\">
<div style=\"background-color: rgba(85, 85, 85, 0.9); color: rgb(51, 51, 51); height: 100vh; left: 0px; position: fixed; top: 0px; -webkit-font-smoothing: antialiased; width: 100vw; z-index: 1;\">
<section style=\"background-color: white; border-radius: 8px; left: 50%; position: absolute; top: 50%; transform: translate(-50%, calc(-50% - 32px)); padding-bottom: 10px; width: 414px;\">
<div style=\"border-bottom: 1px solid rgb(239, 239, 239); display: flex; height: 60px; justify-content: flex-end; padding: 0px 24px;\">
<div role=\"button\" tabindex=\"0\" style=\"cursor: pointer; fill: rgb(142, 142, 142); margin-top: 20px;\">
<svg class=\"gUZ B9u\" height=\"18\" width=\"18\" viewBox=\"0 0 24 24\" aria-label=\"Cancel\" role=\"img\">
<path d=\"M15.18 12l7.16-7.16c.88-.88.88-2.3 0-3.18-.88-.88-2.3-.88-3.18 0L12 8.82 4.84 1.66c-.88-.88-2.3-.88-3.18 0-.88.88-.88 2.3 0 3.18L8.82 12l-7.16 7.16c-.88.88-.88 2.3 0 3.18.44.44 1.01.66 1.59.66.58 0 1.15-.22 1.59-.66L12 15.18l7.16 7.16c.44.44 1.01.66 1.59.66.58 0 1.15-.22 1.59-.66.88-.88.88-2.3 0-3.18L15.18 12z\"></path></svg></div>
<div style=\"font-size: 21px; font-weight: bold; left: 50%; margin-top: 20px; position: absolute; transform: translateX(-50%);\">More information</div></div>
<div style=\"max-height: calc(60vh - 60px); overflow-y: auto; padding: 0px 24px 24px;\">";

$insert.="<h2 style=\"font-size: 16px; margin-top: 15px;\">".$desc."</h2>";
$insert.="<div style=\"font-size: 16px; margin-top: 12px;\">Find this Pin and more on<a href=\"".$url."\" style=\"font-weight: bold; text-decoration: none;\">Nifty Gift Ideas</a> by";
$insert.="<a href=\"/".$fn3."/\" style=\"font-weight: bold; text-decoration: none;\">Nifty</a>.</div>
<div style=\"font-size: 18px; font-weight: bold; margin-top: 16px;\">Tags</div><div style=\"display: flex; flex-wrap: wrap;\">";


$links="";
if (strpos($ex[$i],",\"annotations_with_links\":{")!==FALSE) {$links=substr($ex[$i],strpos($ex[$i],",\"annotations_with_links\":{")+27);$links=substr($links,0,strpos($links,"}}"));}
else if (strpos($ex[$i],"{\"annotations_with_links\":{")!==FALSE) {$links=substr($ex[$i],strpos($ex[$i],"{\"annotations_with_links\":{")+27);$links=substr($links,0,strpos($links,"}}"));}
else {$links=substr($ex[$i],strpos($ex[$i],",\"annotations_with_links\":")+27);$links=substr($links,0,strpos($links,"}}"));}
$ex2=explode("{",$links);
$y=count($ex2);

for($j=1;$j<$y;$j++) {
$p1="";$p2="";
$p1=substr($ex2[$j],strpos($ex2[$j],"\"url\":\"")+7);
$p1=substr($p1,0,strpos($p1,"\","));
$p2=substr($ex2[$j],strpos($ex2[$j],"\"name\":\"")+8);
$p2=substr($p2,0,strpos($p2,"}"));
$cheat.=" ".$p2;
$insert.="<a href=\"".$p1."/\" class=\"vaseCarousel_vaseTagLink\" target=\"_self\">".$p2."</a>";
$insert.="<div class=\"FNs Hb9 gpV hA- hDW mix wYR zI7 iyn Hsu\"><a href=\"".$p1."\" rel=\"tag\"><div class=\"tBJ dyH iFc SMy MF7 B9u DrD IZT mWe\">".$p2."</div></a></div>";
}

$insert.="<div style=\"font-size: 18px; font-weight: bold; margin-top: 16px;\">What others are saying</div>
<h4 data-test-id=\"vasedesc\" style=\"font-size: 16px; font-weight: normal; margin-top: 12px;\">".$desc."</h4></div></section></div></div>
<div class=\"zI7 iyn Hsu\" style=\"min-width: 100px;\">
<div role=\"button\" tabindex=\"0\" style=\"cursor: pointer;\">
<div class=\"Pj7 sLG XiG ZKv mix m1e\">
<div class=\"GrowthUnauthPinImage\">

<a href=\"/pin/".$id."\" target=\"_self\" title=\"".$desc."\" style=\"background: rgb(204, 208, 216);\">
<div class=\"Pj7 sLG XiG sjM gL3 m1e\" style=\"width: 100px; height: 150px;\">
<div style=\"margin-top: 0px; margin-left: -25px;\">
<div class=\"Pj7 sLG XiG sjM gL3 m1e\" style=\"width: 150px; height: 150px;\">";

$image=substr($ex[$i],strpos($ex[$i],"\"images\":{")+10);
$image=substr($image,0,strpos($image,"}}"));
$orig=substr($image,strpos($image,"\"orig\":{")+8);
$orig=substr($orig,strpos($orig,"\"url\":\"")+7);
$orig=substr($orig,0,strpos($orig,"}"));
$t236=substr($image,strpos($image,"\"236x\":{")+8);
$t236=substr($t236,strpos($t236,"\"url\":\"")+7);
$t236=substr($t236,0,strpos($t236,"}"));



$insert.="<img alt=\"".$desc.$cheat."\" class=\"GrowthUnauthPinImage__Image\" src=\"".$t236."\" srcset=\"".$t236." 1x, ".$t236." 2x\"></div></div></div></a>
<div class=\"Jea MIw Rym hA- ojN wYR zI7 iyn Hsu\">";


//if video
//$insert.="<div class=\"Jea Lfz gjz mQ8 prG wYR zI7 iyn Hsu\" style=\"height: 24px;\"><svg class=\"gUZ erh U9O kVc\" height=\"16\" width=\"16\" viewBox=\"0 0 24 24\" aria-label=\"Video camera icon\" role=\"img\"><path d=\"M16 8v8c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V8c0-1.1.9-2 2-2h11c1.1 0 2 .9 2 2zm6.18-.38l-3.67 3.26a1.5 1.5 0 0 0 0 2.24l3.67 3.26c.32.28.82.04.82-.39V8.01c0-.43-.5-.67-.82-.39z\"></path></svg><div class=\"kMA zI7 iyn Hsu\"><div class=\"tBJ dyH iFc SMy _yT erh DrD IZT swG z-6\" title=\"1:32\">1:32</div></div></div>";

$insert.="</div></div></div></div>
<button class=\"PinLandingCloseup__similarPin__button\">More information</button></div></div></div>";
if ($i%3==0) {$insert.="</div></div></div>";}
}


$insert.="</div></section></div><div><section style=\"background-color: rgb(239, 239, 239); padding-top: 35px;\">
<div class=\"ck1 n9m zI7 iyn Hsu\">
<h2 class=\"gridCentered\">People also love these ideas</h2></div><section class=\"gridCentered\" data-test-id=\"pinGrid\" style=\"margin-top: 16px;\">";

$insert.=$sty7;
$insert.="<div class=\"Grid__Container\" style=\"width: 736px; height: 6429px;\"><div>";
$top0=0;$top1=0;$top2=0;$top3=0;

for($i=7;$i<$z;$i++) {
$cheat="";
$board=substr($ex[$i],strpos($ex[$i],"\"board\":{")+0);
$name=substr($board,strpos($board,"\"name\":\"")+7);
$name=substr($name,0,strpos($name,"\","));
$url=substr($board,strpos($board,"\"url\":\"")+7);
$url=substr($url,0,strpos($url,"\","));
$id=substr($board,strpos($board,"\"id\":\"")+5);
$id=substr($id,0,strpos($id,"\","));
$desc=substr($ex[$i],strpos($ex[$i],"\"description\":")+15);
$desc=substr($desc,0,strpos($desc,"\","));
$fn3=substr($ex[$i],strpos($ex[$i],"\"full_name\":")+13);
$fn3=substr($fn3,0,strpos($fn3,"\","));
$fn4=substr($ex[$i],strpos($ex[$i],"\"username\":")+12);
$fn4=substr($fn3,0,strpos($fn4,"}"));

$image=substr($ex[$i],strpos($ex[$i],"\"images\":{")+10);
$image=substr($image,0,strpos($image,"}}"));
$orig=substr($image,strpos($image,"\"orig\":{")+8);
$orig=substr($orig,strpos($orig,"\"url\":\"")+7);
$orig=substr($orig,0,strpos($orig,"}"));
$t236=substr($image,strpos($image,"\"236x\":{")+8);
$ih3=substr($t236,strpos($t236,"\"height\":")+9);
$ih3=substr($ih3,0,strpos($ih3,"}"));
$t236=substr($t236,strpos($t236,"\"url\":\"")+7);
$t236=substr($t236,0,strpos($t236,"}"));

$jump=150;
$left=0;$tpo=0;
//if (strlen($text)>70) {$jump+=16;}
if ($top0<=$top1 && $top0<=$top2 && $top0<=$top3) {$left=0;$top=$top0;$top0+=$ih3+$jump;}
else if ($top1<=$top2 && $top1<=$top3) {$left=250;$top=$top1;$top1+=$ih3+$jump;}
else if ($top2<=$top3) {$left=500;$top=$top2;$top2+=$ih3+$jump;}
else {$left=750;$top=$top3;$top3+=$ih3+$jump;}


//$owner=substr($ex[$i],strpos($ex[$i],"\"owner\":{")+9);
$cat=substr($board,strpos($board,"\"name\":")+8);
$cat=substr($cat,0,strpos($cat,"\","));



$insert.="<div class=\"Grid__Item\" style=\"top: ".$top."px; left: ".$left."px;\">
<div class=\"PinGridInner__brioPin GrowthUnauthPin_brioPin relatedPin\" data-test-id=\"pin\" data-test-pin-id=\"831054937458607019\" role=\"button\" tabindex=\"0\" style=\"width: 236px;\">
<div class=\"GrowthUnauthPinImage\">
<a href=\"/pin/831054937458607019/\" target=\"_self\" title=\"".$desc."\" style=\"background: rgb(253, 253, 253);\">
<img alt=\"".$desc.$cheat."\" class=\"GrowthUnauthPinImage__Image\" src=\"".$t236."\" style=\"height: ".$ih3."px;\"></a></div>
<figcaption>
<h3 class=\"PinDescription__desc PinDescription__1LineDesc\" data-test-id=\"desc\">".$desc."</h3></figcaption>
<div class=\"vaseCarousel_vasetags_container\" data-test-id=\"vasetags\" style=\"height: 30px;\">
<div class=\"vaseCarousel_vasetags_wrapper\">";


$links="";
if (strpos($ex[$i],",\"annotations_with_links\":{")!==FALSE) {$links=substr($ex[$i],strpos($ex[$i],",\"annotations_with_links\":{")+27);$links=substr($links,0,strpos($links,"}}"));}
else if (strpos($ex[$i],"{\"annotations_with_links\":{")!==FALSE) {$links=substr($ex[$i],strpos($ex[$i],"{\"annotations_with_links\":{")+27);$links=substr($links,0,strpos($links,"}}"));}
else {$links=substr($ex[$i],strpos($ex[$i],",\"annotations_with_links\":")+27);$links=substr($links,0,strpos($links,"}}"));}
$ex2=explode("{",$links);
$y=count($ex2);

for($j=1;$j<$y;$j++) {
$p1="";$p2="";
$p1=substr($ex2[$j],strpos($ex2[$j],"\"url\":\"")+7);
$p1=substr($p1,0,strpos($p1,"\","));
$p2=substr($ex2[$j],strpos($ex2[$j],"\"name\":\"")+8);
$p2=substr($p2,0,strpos($p2,"}")-1);
$cheat.=" ".$p2;
$insert.="<a href=\"".$p1."\" class=\"vaseCarousel_vaseTagLink\" target=\"_self\" style=\"background-color: rgb(255, 255, 255);\">".$p2."</a>";
}


$insert.="<svg class=\"Hn_ gUZ B9u\" height=\"8\" width=\"8\" viewBox=\"0 0 24 24\" aria-label=\"Forward\" role=\"img\">
<path d=\"M6.72 24c.57 0 1.14-.22 1.57-.66L19.5 12 8.29.66c-.86-.88-2.27-.88-3.14 0-.87.88-.87 2.3 0 3.18L13.21 12l-8.06 8.16c-.87.88-.87 2.3 0 3.18.43.44 1 .66 1.57.66\"></path></svg></a></div></div>
<p class=\"PinDescription__desc PinDescription__2LineDesc\" data-test-id=\"desc\">".$desc."</p>
<div class=\"pinDescription_pinnerBoardAttribution\">
<a class=\"underlineLink\" href=\"/".$fn4."/\" rel=\"\">";

$pinner=substr($ex[$i],strpos($ex[$i],"\"pinner\":{"));
$purl=substr($pinner,strpos($pinner,"\"image_small_url\":")+19);
$purl=substr($purl,0,strpos($purl,"\","));
//echo "<br>purl:".$purl;


$insert.="
<div class=\"pinDescription_pinnerProfileImage\" style=\"background-image: url(&quot;".$purl."&quot;); background-size: cover;\"></div></a>
<div class=\"pinDescription_attribute_container\">
<a class=\"underlineLink pinDescription_text\" href=\"/".$fn4."/\" rel=\"\" style=\"font-weight: bolder;\">".$fn3."</a>";
//echo "<br>cat".$cat;
//echo "<br>url".$url;
//exit;
$insert.="<a class=\"pinDescription_text pinDescription_text_board underlineLink\" href=\"".$url."\" rel=\"\">".$cat."</a></div></div>
<button data-test-id=\"seemoretoggle\" class=\"PinDescriptionSeeMore_summary underlineLink\" style=\"background-color: transparent; border: 0px; color: rgb(51, 51, 51); font-size: 12px; font-weight: bold; display: flex; justify-content: flex-end; height: 15px; margin: 0px 0px 3px; outline: none; padding: 0px; -webkit-font-smoothing: antialiased;\">See more
<span style=\"margin-left: 4px;\">
<svg class=\"gUZ B9u\" height=\"10\" width=\"10\" viewBox=\"0 0 24 24\" aria-label=\"expand\" role=\"img\">
<path d=\"M12 19.5L.66 8.29c-.88-.86-.88-2.27 0-3.14.88-.87 2.3-.87 3.18 0L12 13.21l8.16-8.06c.88-.87 2.3-.87 3.18 0 .88.87.88 2.28 0 3.14L12 19.5z\"></path></svg></span></button></div></div>";
}

$insert.="</div></div></section></section></div></div></div></div></div></main><header data-test-id=\"unauthHeader\" id=\"HeaderContent\" style=\"top: 0px; background-color: rgb(255, 255, 255); border-bottom: 1px solid rgb(204, 204, 204); width: 100%; z-index: 675; position: absolute;\"><div style=\"height: 64px; display: flex; align-items: center; margin: 0px 16px;\"><div style=\"order: 1;\"><div style=\"display: inline-flex;\"><div class=\"UnauthHeader__rightContentContainer\" style=\"position: relative;\"><div data-test-id=\"signupButton\" class=\"zI7 iyn Hsu\"><button aria-label=\"\" class=\"red headerSignupButton active\" type=\"button\" style=\"border: 0px; height: 40px; display: inline-block; border-radius: 4px; -webkit-font-smoothing: antialiased; padding: 0px; font-size: 14px; font-weight: bold; outline: none; box-shadow: none; cursor: pointer; margin-top: 0px; vertical-align: middle; text-align: center; background-color: rgb(230, 0, 35); color: rgb(255, 255, 255); line-height: 36px; width: 128px; margin-right: 8px;\">Sign up</button></div><div data-test-id=\"loginButton\" class=\"Jea XiG gjz mQ8 zI7 iyn Hsu\"><button aria-label=\"\" class=\"lightGrey headerLoginButton active\" type=\"button\" style=\"border: 0px; height: 40px; display: inline-block; border-radius: 4px; -webkit-font-smoothing: antialiased; padding: 0px; font-size: 14px; font-weight: bold; outline: none; box-shadow: none; cursor: pointer; margin-top: 0px; vertical-align: middle; text-align: center; background-color: rgb(235, 235, 235); color: rgb(68, 68, 68); width: 128px;\">Log in</button></div><div><div class=\"Jea hDW mQ8 n9m zI7 iyn Hsu\"><button aria-label=\"Setting button\" class=\"rYa kVc adn yQo qrs BG7\" type=\"button\"><div class=\"x8f INd _O1 gjz mQ8 OGJ YbY\" style=\"height: 32px; width: 32px;\"><div class=\"INd zI7 iyn Hsu\"><svg class=\"gUZ pBj U9O kVc\" height=\"16\" width=\"16\" viewBox=\"0 0 24 24\" aria-hidden=\"true\" aria-label=\"\" role=\"img\"><path d=\"M12 9c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3M3 9c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm18 0c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3z\"></path></svg></div></div></button></div></div></div></div></div><div style=\"display: flex; flex: 1 1 0%; align-items: center;\"><a class=\"Wk9 xQ4 WMU iyn ljY kVc\" href=\"/\"><span style=\"cursor: pointer; display: block; float: left; height: 28px; width: 28px; vertical-align: middle;\"><svg height=\"28\" viewBox=\"3 3 70 70\" width=\"28\" style=\"display: block;\"><path d=\"M27.5 71c3.3 1 6.7 1.6 10.3 1.6C57 72.6 72.6 57 72.6 37.8 72.6 18.6 57 3 37.8 3 18.6 3 3 18.6 3 37.8c0 14.8 9.3 27.5 22.4 32.5-.3-2.7-.6-7.2 0-10.3l4-17.2s-1-2-1-5.2c0-4.8 3-8.4 6.4-8.4 3 0 4.4 2.2 4.4 5 0 3-2 7.3-3 11.4C35.6 49 38 52 41.5 52c6.2 0 11-6.6 11-16 0-8.3-6-14-14.6-14-9.8 0-15.6 7.3-15.6 15 0 3 1 6 2.6 8 .3.2.3.5.2 1l-1 3.8c0 .6-.4.8-1 .4-4.4-2-7-8.3-7-13.4 0-11 7.8-21 22.8-21 12 0 21.3 8.6 21.3 20 0 12-7.4 21.6-18 21.6-3.4 0-6.7-1.8-7.8-4L32 61.7c-.8 3-3 7-4.5 9.4z\" fill=\"#e60023\" fill-rule=\"evenodd\"></path></svg></span></a><a href=\"/\" role=\"button\" style=\"text-decoration: none;\"><span class=\"UnauthHeader__discoverText\" style=\"color: rgb(67, 67, 67); font-size: 18px; font-weight: bold; margin-left: 14px; -webkit-font-smoothing: antialiased;\">Pinterest</span></a><div style=\"flex: 1 1 0%;\">";

$insert.=$sty8;


$insert.="<div class=\"OpenSearchForm\" style=\"box-sizing: border-box; display: block; float: none; height: auto; margin-left: 16px; margin-right: 16px; position: relative; text-align: left; width: auto;\"><form name=\"search\"><div class=\"typeaheadField guided\" style=\"display: block; float: none; position: relative; width: 100%;\"><div class=\"tokenizedInputWrapper\" role=\"search\" style=\"background-color: rgb(239, 239, 239); border-radius: 4px; box-sizing: border-box; height: 40px; position: relative; display: flex; align-items: center;\"><em></em><div style=\"margin-left: 16px;\"><svg class=\"gUZ B9u U9O kVc\" height=\"20\" width=\"20\" viewBox=\"0 0 24 24\" aria-label=\"search\" role=\"img\"><path d=\"M10 16c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6m13.12 2.88l-4.26-4.26A9.842 9.842 0 0 0 20 10c0-5.52-4.48-10-10-10S0 4.48 0 10s4.48 10 10 10c1.67 0 3.24-.41 4.62-1.14l4.26 4.26a3 3 0 0 0 4.24 0 3 3 0 0 0 0-4.24\"></path></svg></div><div class=\"tokenizedInput guided typeaheadWithTitles\" style=\"margin-left: 11px; overflow: hidden; flex: 1 1 0%; display: flex; align-items: center;\"><div class=\"scrollWrapper\" style=\"overflow: hidden; flex: 1 1 0%;\"><ul class=\"tokensWrapper\" style=\"cursor: text; float: left; min-height: 1px; white-space: nowrap; width: 100%; left: 0px; overflow: visible; position: relative; transition-duration: 0.25s; transition-property: left;\"><li class=\"tokenizedItem inputToken\" style=\"color: rgb(0, 0, 0); display: inline-block; margin: 0px; overflow: hidden; width: 100%; white-space: nowrap; vertical-align: middle;\"><label><div class=\"NVN zI7 iyn Hsu\">Search</div><input autocomplete=\"off\" class=\"OpenSearchBoxInput\" name=\"q\" placeholder=\"Search for easy dinners, fashion, etc.\" type=\"text\" value=\"\" style=\"background: transparent; border: 0px; border-radius: 3px; box-shadow: none; box-sizing: border-box; font-size: 16px; font-weight: 600; height: 40px; line-height: 20px; overflow: hidden; width: 100%;\"></label></li></ul></div></div></div><div><div class=\"OpenTypeahead guided typeaheadWithTitles\" style=\"margin: 41px 0px 0px; position: absolute; border-radius: 0px 0px 6px 6px; top: 0px;\"><ul class=\"results\"></ul></div></div></div></form></div></div></div></div>
</header><div><div data-test-id=\"giftWrap\" role=\"dialog\" style=\"background: rgba(0, 0, 0, 0.65); transition: height 0.5s cubic-bezier(0.26, 0.87, 0.74, 0.93) 0s; backface-visibility: hidden; bottom: 0px; color: rgb(255, 255, 255); height: 0px; position: fixed; width: 100%;\"><div data-test-id=\"quarterBanner\" class=\"gridCentered\" style=\"transition: opacity 0.5s linear 0s; pointer-events: none; visibility: hidden; opacity: 0; height: 0px;\"><div class=\"Jea b8T gjz zI7 iyn Hsu\" style=\"height: 64px; width: 100%;\"><div style=\"color: rgb(255, 255, 255); display: inline-block; font-size: 24px; font-weight: 500; vertical-align: middle; font-style: normal; font-stretch: normal; line-height: normal; text-align: left;\">Explore more ideas with a Pinterest account</div><div class=\"Jea b8T hA- wYR zI7 iyn Hsu\"><button aria-label=\"\" class=\"white active\" type=\"button\" style=\"border: 0px; height: 44px; display: inline-block; border-radius: 4px; -webkit-font-smoothing: antialiased; padding: 0px 14px; font-size: 16px; font-weight: bold; outline: none; box-shadow: none; cursor: pointer; margin-top: 0px; vertical-align: middle; text-align: center; background-color: rgb(255, 255, 255); color: rgb(68, 68, 68); line-height: 36px; min-width: 128px; margin-right: 5px; white-space: nowrap;\">Sign up</button><button aria-label=\"\" class=\"darkGrey active\" type=\"button\" style=\"border: 1px solid rgb(255, 255, 255); height: 44px; display: inline-block; border-radius: 4px; -webkit-font-smoothing: antialiased; padding: 0px 14px; font-size: 16px; font-weight: bold; outline: none; box-shadow: none; cursor: pointer; margin-top: 0px; vertical-align: middle; text-align: center; background-color: transparent; color: rgb(255, 255, 255); line-height: 36px; min-width: 128px; margin-right: 5px;\">Log in</button></div></div></div><div data-test-id=\"fullBanner\" role=\"dialog\" style=\"transition: opacity 0.5s linear 0s; pointer-events: none; visibility: hidden; opacity: 0; height: 0px;\"><div role=\"dialog\" style=\"pointer-events: none; visibility: hidden; opacity: 0;\"><div data-test-id=\"signup\" style=\"background-color: rgb(255, 255, 255); border-radius: 8px; position: relative; text-align: center; width: 484px; margin: auto; min-height: 450px; box-shadow: rgba(0, 0, 0, 0.45) 0px 2px 10px;\"><div style=\"min-height: 400px; padding: 20px 10px 24px;\"><div style=\"display: block; height: 45px; margin: 5px auto 8px; width: 45px;\"><svg height=\"48\" viewBox=\"-3 -3 82 82\" width=\"48\" style=\"display: block;\"><circle cx=\"38\" cy=\"38\" fill=\"white\" r=\"40\"></circle><path d=\"M27.5 71c3.3 1 6.7 1.6 10.3 1.6C57 72.6 72.6 57 72.6 37.8 72.6 18.6 57 3 37.8 3 18.6 3 3 18.6 3 37.8c0 14.8 9.3 27.5 22.4 32.5-.3-2.7-.6-7.2 0-10.3l4-17.2s-1-2-1-5.2c0-4.8 3-8.4 6.4-8.4 3 0 4.4 2.2 4.4 5 0 3-2 7.3-3 11.4C35.6 49 38 52 41.5 52c6.2 0 11-6.6 11-16 0-8.3-6-14-14.6-14-9.8 0-15.6 7.3-15.6 15 0 3 1 6 2.6 8 .3.2.3.5.2 1l-1 3.8c0 .6-.4.8-1 .4-4.4-2-7-8.3-7-13.4 0-11 7.8-21 22.8-21 12 0 21.3 8.6 21.3 20 0 12-7.4 21.6-18 21.6-3.4 0-6.7-1.8-7.8-4L32 61.7c-.8 3-3 7-4.5 9.4z\" fill=\"#e60023\" fill-rule=\"evenodd\"></path></svg></div><div style=\"margin: 0px auto 18px; width: 400px;\"></div><div style=\"margin: 0px auto 18px; width: 270px;\"><h3 style=\"text-align: center; color: rgb(51, 51, 51); font-size: 16px; font-weight: normal; margin: -15px 0px 32px;\">Sign up to see more</h3></div><div data-test-id=\"signup\" style=\"margin: 0px auto; position: relative; text-align: center;\"><div style=\"margin: 0px auto; width: 268px;\"><div><div data-test-id=\"emailSignUpButton\"><button class=\"RCK Hsu mix Vxj aZc GmH adn Il7 Jrn hNT iyn BG7 gn8 L4E kVc\" type=\"button\"><div class=\"tBJ dyH iFc SMy yTZ erh tg7 mWe\">Continue with email</div></button></div><div style=\"margin-top: 10px;\"><div style=\"position: relative;\"><div><button aria-label=\"\" class=\"FacebookConnectButton active\" type=\"button\" style=\"border: 0px; height: 40px; display: block; border-radius: 8px; -webkit-font-smoothing: antialiased; padding: 0px 0px 0px 8px; font-size: 15px; font-weight: normal; outline: none; box-shadow: none; cursor: pointer; margin-top: 0px; vertical-align: middle; text-align: left; background-clip: padding-box; background-color: rgb(24, 119, 242); position: absolute; transition: opacity 0.2s linear 0s; width: 100%;\"><svg class=\"gUZ erh\" height=\"24\" width=\"24\" viewBox=\"0 0 24 24\" aria-label=\"facebook\" role=\"img\"><path d=\"M17.75 3.984l-2.312.001c-1.811 0-2.163.842-2.163 2.077v2.724h4.323l-.563 4.267h-3.76V24H8.769V13.053H5V8.786h3.769V5.64C8.769 1.988 11.05 0 14.383 0c1.596 0 2.967.116 3.367.168v3.816z\"></path></svg><span style=\"color: rgb(255, 255, 255); display: inline-block; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen-Sans, Ubuntu, Cantarell, &quot;Fira Sans&quot;, &quot;Droid Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;ヒラギノ角ゴ Pro W3&quot;, &quot;Hiragino Kaku Gothic Pro&quot;, メイリオ, Meiryo, &quot;ＭＳ Ｐゴシック&quot;, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;; font-size: 15px; font-weight: bold; letter-spacing: normal; line-height: 16px; padding-left: 4px; padding-top: 1px; text-align: center; vertical-align: text-bottom; -webkit-font-smoothing: auto; white-space: normal; width: 88%;\">Continue with Facebook</span></button><div style=\"height: 40px; text-align: left;\"><div class=\"fb-login-button fb_iframe_widget\" data-scope=\"public_profile,email,user_likes,user_birthday,user_friends\" onlogin=\"checkLoginState\" data-button-type=\"continue_with\" data-use-continue-as=\"true\" data-width=\"268px\" data-size=\"large\" login_text=\"\" fb-xfbml-state=\"rendered\" fb-iframe-plugin-query=\"app_id=274266067164&amp;button_type=continue_with&amp;container_width=268&amp;locale=en_US&amp;scope=public_profile%2Cemail%2Cuser_likes%2Cuser_birthday%2Cuser_friends&amp;sdk=joey&amp;size=large&amp;use_continue_as=true&amp;width=268px\"><span style=\"vertical-align: bottom; width: 268px; height: 40px;\"><iframe name=\"f3fe77309d235e8\" height=\"1000px\" title=\"fb:login_button Facebook Social Plugin\" frameborder=\"0\" allowtransparency=\"true\" allowfullscreen=\"true\" scrolling=\"no\" allow=\"encrypted-media\" src=\"https://www.facebook.com/v2.2/plugins/login_button.php?app_id=274266067164&amp;button_type=continue_with&amp;channel=https%3A%2F%2Fstaticxx.facebook.com%2Fconnect%2Fxd_arbiter.php%3Fversion%3D45%23cb%3Df385ea73e0474c8%26domain%3Dwww.pinterest.com%26origin%3Dhttps%253A%252F%252Fwww.pinterest.com%252Ff28e331803852f8%26relation%3Dparent.parent&amp;container_width=268&amp;locale=en_US&amp;scope=public_profile%2Cemail%2Cuser_likes%2Cuser_birthday%2Cuser_friends&amp;sdk=joey&amp;size=large&amp;use_continue_as=true&amp;width=268px\" style=\"border: none; visibility: visible; width: 268px; height: 40px;\" class=\"\"></iframe></span></div></div></div></div><div style=\"height: 10px;\"></div><div><button aria-label=\"\" class=\"GoogleConnectButton active\" id=\"googleConnectButton\" type=\"button\" style=\"border: 0px; height: 40px; display: block; border-radius: 4px; -webkit-font-smoothing: antialiased; padding: 1px; font-size: 15px; font-weight: bold; outline: none; box-shadow: none; cursor: pointer; margin-top: 0px; vertical-align: middle; text-align: left; background-color: rgb(239, 239, 239); margin-right: 0px; background-clip: padding-box; transition: opacity 0.2s linear 0s; position: relative; width: 100%;\"><div style=\"background-color: transparent; border-radius: 2px; display: inline-block; height: 24px; margin-left: 12px; text-align: center; width: 24px;\"><div style=\"position: relative; margin: 2px auto auto;\"><svg height=\"20\" viewBox=\"0 0 512 512\" width=\"20\" xmlns=\"http://www.w3.org/2000/svg\"><g fill=\"none\" fill-rule=\"evenodd\"><path d=\"M482.56 261.36c0-16.73-1.5-32.83-4.29-48.27H256v91.29h127.01c-5.47 29.5-22.1 54.49-47.09 71.23v59.21h76.27c44.63-41.09 70.37-101.59 70.37-173.46z\" fill=\"#4285f4\"></path><path d=\"M256 492c63.72 0 117.14-21.13 156.19-57.18l-76.27-59.21c-21.13 14.16-48.17 22.53-79.92 22.53-61.47 0-113.49-41.51-132.05-97.3H45.1v61.15c38.83 77.13 118.64 130.01 210.9 130.01z\" fill=\"#34a853\"></path><path d=\"M123.95 300.84c-4.72-14.16-7.4-29.29-7.4-44.84s2.68-30.68 7.4-44.84V150.01H45.1C29.12 181.87 20 217.92 20 256c0 38.08 9.12 74.13 25.1 105.99l78.85-61.15z\" fill=\"#fbbc05\"></path><path d=\"M256 113.86c34.65 0 65.76 11.91 90.22 35.29l67.69-67.69C373.03 43.39 319.61 20 256 20c-92.25 0-172.07 52.89-210.9 130.01l78.85 61.15c18.56-55.78 70.59-97.3 132.05-97.3z\" fill=\"#ea4335\"></path><path d=\"M20 20h472v472H20V20z\"></path></g></svg></div></div><span style=\"color: rgb(51, 51, 51); display: inline-block; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen-Sans, Ubuntu, Cantarell, &quot;Fira Sans&quot;, &quot;Droid Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;ヒラギノ角ゴ Pro W3&quot;, &quot;Hiragino Kaku Gothic Pro&quot;, メイリオ, Meiryo, &quot;ＭＳ Ｐゴシック&quot;, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;; position: absolute; text-align: center; top: 50%; font-size: 16px; transform: translateY(-50%) translateX(-37px); vertical-align: top; -webkit-font-smoothing: auto; width: 100%;\">Continue with Google</span></button></div></div></div><div style=\"margin-top: 16px;\"><span class=\"\" style=\"-webkit-font-smoothing: antialiased; font-size: 11px; font-weight: normal; text-align: center; transition: opacity 0.2s linear 0s; color: rgb(142, 142, 142); width: 224px;\"><span>By continuing, you agree to Pinterest's <a data-test-id=\"tos\" href=\"/_/_/about/terms-service/\" target=\"_blank\">Terms of Service</a>, <a data-test-id=\"privacy\" href=\"/_/_/about/privacy/\" target=\"_blank\">Privacy Policy</a></span></span></div><div><div style=\"border-bottom: 1px solid rgb(239, 239, 239); margin: 20px 0px 15px -68px; width: 404px;\"></div><div><div style=\"margin: 0px auto 5px; width: fit-content; align-items: baseline;\"><div class=\"zI7 iyn Hsu\"><a style=\"color: rgb(51, 51, 51); cursor: pointer; margin-left: 5px;\">Already a member? Log in</a></div></div></div></div></div></div></div></div></div></div></div></div><button aria-label=\"\" class=\"noButtonStyles active\" type=\"button\" style=\"background: none; border: none; padding: 0px; text-align: left;\"><div style=\"background-color: rgb(255, 255, 255); border-radius: 4px; box-shadow: rgba(0, 0, 0, 0.1) 0px 2px 0px 0px, rgba(0, 0, 0, 0.04) 0px 0px 0px 0.5px; color: rgb(51, 51, 51); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen-Sans, Ubuntu, Cantarell, &quot;Fira Sans&quot;, &quot;Droid Sans&quot;, &quot;Helvetica Neue Bold&quot;, Helvetica, &quot;ヒラギノ角ゴ Pro W3&quot;, &quot;Hiragino Kaku Gothic Pro&quot;, メイリオ, Meiryo, &quot;ＭＳ Ｐゴシック&quot;, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;; font-size: 12px; font-weight: normal; padding: 2px 5px 3px; position: fixed; right: 10px; bottom: 80px;\">Privacy</div></button></div></div></div><div style=\"bottom: 14px; left: 50%; margin-left: -183px; position: fixed; z-index: 1500;\"></div></div>";


$r=$pre.$insert.$post;
}
else if ($a==="pinterest.com") {
$count=0;
require("pinstyle.php");
$head=substr($c,0,strpos($c,"<head"));
$c=substr($c,strpos($c,"<head"));
$c=$head."<base href=\"https://www.pinterest.com\">".$c;
$pre=substr($c,0,strpos($c,"<div id=\"__PWS_ROOT__\" data-reactcontainer"));
$post=substr($c,strpos($c,"<div id=\"__PWS_ROOT__\" data-reactcontainer"));
$pre.=substr($post,0,strpos($post,"</div"));
$post=substr($post,strpos($post,"</div"));
$json=substr($post,strpos($post,"{"));
$json=substr($json,0,strrpos($json,"</script>"));
$post="</body></html>";
$insert="<div><div><div class=\"zI7 iyn Hsu\">";
$insert.=$sty1;
$insert.="<div>";
$insert.=$sty2;
//this alternates:
$json2=substr($json,strpos($json,"\"prepend\""));
if (strpos($json2,"\"meta_data\":")===FALSE) {
$json2=substr($json2,strpos($json2,"{"));
$json2=substr($json2,strpos($json2,"}"));
$json2=substr($json2,strpos($json2,"{"));
//if (strpos($json2,"\"username\":")>1000) {$json2=" \"username\":".$json2;}
//json3=templatehelp("https://www.pinterest.com/resource/BoardFeedResource/get/?source_url=/tkentguns/firearm-schematics-other-charts/&data=%7B"options":%7B"isPrefetch"&7bfalse,"field_set_key":%22unauth_react","board_id":"579205270765463078","board_title":"Firearm%20schematics,&%20other%20charts.","rank_with_query":null,"prepend":true,"owner":%7B"is_tastemaker":false,"image_medium_url":"https://i.pinimg.com/75x75_RS/ad/39/4b/ad394b909c73071fb8fe29b8c8940885.jpg","type":"user","full_name":"Ted%20Kent","id":"579205339484845123","blocked_by_me":false,"locale":"en-US","image_small_url":"https://i.pinimg.com/30x30_RS/ad/39/4b%2Fad394b909c73071fb8fe29b8c8940885.jpg","is_partner":false,"country":"US","username":"tkentguns","domain_url":null,"image_xlarge_url":"https://i.pinimg.com/280x280_RS/ad/39%2F4b%2Fad394b909c73071fb8fe29b8c8940885.jpg","first_name":"Ted","indexed":true,"last_name":""%7D,"pin_count":108,"add_vase":true,"filter_section_pins":false,"page_size":25%7D,"context":%7B%7D%7D&_=1580943240432")
$fn=substr($json,strpos($json,",\"username\":\"")+13);
$fn=substr($fn,0,strpos($fn,",")-1);
$path=substr($json,strpos($json,",\"slug\":\"")+9);
$path=substr($path,0,strpos($path,",")-1);
$id=substr($json,strpos($json,",\"id\":\"")+7);
$id=substr($id,0,strpos($id,",")-1);
$id2=substr($json,strpos($json,"\"id\":")+6);
$id2=substr($id2,strpos($id2,"\"id\":")+6);
$id2=substr($id2,0,strpos($id2,",")-1);
if ($id===$id2) {
$id3=substr($json,strpos($json,"\"id\":")+6);
$id3=substr($id3,strpos($id3,"\"id\":")+6);
$id3=substr($id3,strpos($id3,"\"id\":")+6);
$id3=substr($id3,0,strpos($id3,",")-1);
$id2=$id3;
}
//echo "<br>id1:".$id;
//echo "<br>id2:".$id2;
//echo "<br>id3:".$id3;
$idc=$id;
if (substr_count($json,$id2)<substr_count($json,$id)){$id=$id2;} else {$idc=$id2;}

$btitle=substr($json,strpos($json,",\"name\":\"")+9);
$btitle=urlencode(substr($btitle,0,strpos($btitle,",")-1));
//https://www.pinterest.com/resource/BoardFeedResource/get/?source_url=%2Ftkentguns%2Ffirearm-schematics-other-charts%2F&
//data=%7B%22options%22%3A%7B%22isPrefetch%22%3Afalse%2C%22field_set_key%22%3A%22unauth_react%22%2C%22board_id%22%3A%22579205270765463078
//%22%2C%22rank_with_query%22%3Anull%2C%22prepend%22%3Atrue%2C%22owner%22%3A%7B%22is_tastemaker%22%3Afalse%2C%22type%22%3A%22user%22%2C%22
//id%22%3A%22579205339484845123%22%2C%22blocked_by_me%22%3Afalse%2C%22locale%22%3A%22en-US%22%2C%22is_partner%22%3Afalse%2C%22domain_url%22%3Anull%2C%22indexed%22%3Atrue%7D%2C%22add_vase%22%3Atrue%2C%22filter_section_pins%22%3Afalse%2C%22page_size%22%3A25%7D%2C%22context%22%3A%7B%7D%
$dataurl="https://www.pinterest.com/resource/BoardFeedResource/get/?source_url=/".$fn;
$dataurl.="/".$path."&data=%7B%22options%22%3A%7B%22isPrefetch%22%3Afalse%2C%22field_set_key%22%3A%22unauth_react%22%2C%22board_id%22%3A%22";
$dataurl.=$id."%22%2C%22board_title%22%3A%22".$btitle."%22%2C%22";
$dataurl.="rank_with_query%22%3Anull%2C%22prepend%22%3Atrue%2C%22owner%22%3A%7B%22is_tastemaker%22%3Afalse%2C%22type%22%3A%22user%22%2C%22id%22%3A%22".$id."%22%2C%22blocked_by_me%22%3Afalse%2C%22locale%22%3A%22en-US%22%2C%22is_partner%22%3Afalse%2C%22domain_url%22%3Anull%2C%22indexed%22%3Atrue%7D%2C%22add_vase%22%3Atrue%2C%22filter_section_pins%22%3Afalse%2C%22page_size%22%3A25%7D%2C%22context%22%3A%7B%7D%7D&_=1";
//echo $dataurl;
$json2=templatehelp($dataurl);
$f=0;
while ((strpos($json2,"\"data\"[{\"id\":")>0 || strpos($json2,"\"data\"[{\"description\":")>0) && $f<10 )
{$json2=templatehelp($dataurl);$f++;}
//if (strpos($json2,"\"status\":\"failure\"")>0) {echo "<br>id:".$id}
//$json2=str_replace("\"id\":\"".$idc,"\"id2\":\"".$idc,$json2);
//$json2=str_replace("\"id\":\"".$id,"\"id3\":\"".$idc,$json2);

//echo "<br><br>dataurl:".$dataurl;
//echo $json2;

}
$key=substr($json2,strpos($json2,"\"data\":[{")+9);
$key="{".substr($key,0,strpos($key,":")+1);
if (strpos($key,"type\"")==2){$key.="\"p";}


//echo $key;
$ex=explode($key,$json2);
$z=count($ex);
//if ($z<3) {$ex=explode("\",\"username\":",$json2);$z=count($ex);$offset=0;echo "hjhhkjhj";}
//if ($z<5) {$ex=explode(",\"username\":",$json2);$z=count($ex);$offset=2;echo "hjhhkjhj";echo "hjhhkjhj";}
//if ($z<5) {echo $json;}

$rep=substr($json,strpos($json,",\"pin_count\":")+13);
$rep=substr($rep,0,strpos($rep,","));

$fv=substr($json,strpos($json,"\"follower_count\":")+17);
$fv=substr($fv,0,strpos($fv,","));

$fn=substr($json,strpos($json,",\"username\":\"")+13);
$fn=substr($fn,0,strpos($fn,",")-1);
$fn2=substr($json,strpos($json,",\"full_name\":\"")+14);
$fn2=substr($fn2,0,strpos($fn2,",")-1);
if (strrpos($fn2,"\"")>=strlen($fn2)-1) {
$fn2=substr($fn2,0,strlen($fn2)-1);}
$insert.="<main style=\"position: relative; top: 64px; padding-top: 5px;\"><header data-test-id=\"boardHeader\" style=\"display: flex; flex-direction: column; margin-top: 20px; justify-content: center; align-items: center; margin-bottom: 28px;\"><div style=\"display: flex; width: 805px; justify-content: space-between;\"><div style=\"display: flex; flex-direction: column; align-self: center;\"><h1 style=\"font-size: 48px;\">Firearm schematics,&amp; other charts.</h1><h2 style=\"margin-top: 4px; margin-bottom: 0px; font-size: 16px; font-weight: normal;\">Collection by 
<a href=\"/".$fn."/\" style=\"color: rgb(51, 51, 51);\">".$fn2."</a></h2><div class=\"Hvp zI7 iyn Hsu\"><div class=\"tBJ dyH iFc SMy yTZ pBj DrD IZT swG\"><div class=\"Jea hs0 zI7 iyn Hsu\"><div class=\"tBJ dyH iFc SMy yTZ pBj DrD IZT mWe\">".$rep."&nbsp;</div> Pins<div class=\"zI7 iyn Hsu\">&nbsp;•&nbsp;</div><div class=\"tBJ dyH iFc SMy yTZ pBj DrD IZT mWe\">".$fv."&nbsp;</div> Followers&nbsp;</div></div></div></div><div style=\"align-items: center; flex-direction: column; align-self: center; display: flex;\">
<a href=\"/".$fn."/\" style=\"width: 90px; margin-bottom: 20px;\">
<div class=\"INd XiG qJc zI7 iyn Hsu\" style=\"width: 100%;\">
<div class=\"Pj7 sLG XiG pJI INd m1e\">
<div class=\"XiG zI7 iyn Hsu\" style=\"background-color: rgb(239, 239, 239); padding-bottom: 100%;\">";
$timg3=substr($json,strpos($json,",\"image_xlarge_url\":")+21);
$timg3=substr($timg3,0,strpos($timg3,",")-1);
$insert.="<img alt=\"".$fn2."\" class=\"hCL kVc L4E MIw\" importance=\"auto\" loading=\"auto\" src=\"".$timg3."\"></div>";
$insert.="<div class=\"KPc MIw ojN Rym p6V QLY\"></div></div></div></a><button class=\"RCK Hsu mix Vxj aZc GmH adn Il7 Jrn hNT iyn BG7 gn8 L4E kVc\" type=\"button\"><div class=\"tBJ dyH iFc SMy yTZ erh tg7 mWe\">Follow</div></button></div></div></header><div class=\"WbA zI7 iyn Hsu\"></div><section class=\"gridCentered\" data-test-id=\"pinGrid\" style=\"margin-top: -20px;\">";
$insert.=$sty3;

$insert.="<div class=\"Grid__Container\" style=\"width: 986px; height: 8588px;\"><div>";
//$insert.=$sty3;

//$ex=explode("{\"username:\"",$json);
//$z=count($ex);

$left=0;
$top0=0;
$top1=0;
$top2=0;
$top3=0;
$oset=0;
//echo $z;
for($i=1;$i<$z;$i++) {
$text="";
$title="";
$text2="";
$seo="";
$ex[$i]=$key.$ex[$i];
$fn=substr($ex[$i],0,strpos($ex[$i],","));
$id=substr($ex[$i],strpos($ex[$i],",\"id\":\"")+7);
$id=substr($id,0,strpos($id,",")-1);
$timg=substr($ex[$i],strpos($ex[$i],",\"image_large_url\":\"")+20);
$timg=substr($timg,0,strpos($timg,",")-1);
$timg2=substr($ex[$i],strpos($ex[$i],",\"image_small_url\":\"")+20);
$timg2=substr($timg2,0,strpos($timg2,",")-1);
$d=substr($ex[$i],strpos($ex[$i],",\"domain\":\"")+11);
$d=substr($d,0,strpos($d,",")-1);
$title=substr($ex[$i],strpos($ex[$i],"\"title\":")+9);
$title=substr($title,0,strpos($title,",")-1);
//if ($title==="title") {$title="";}
//if (strlen($title)==0) {
//text2=substr($ex[$i],strpos($ex[$i],"\"description_html\":")+20);
//$text2=substr($text2,0,strpos($text2,",")-1);
//$title=$text2;}
//$title=str_replace("\\\"","\"",$text);

$text2=substr($ex[$i],strpos($ex[$i],"\"description_html\":")+20);
$text2=substr($text2,0,strpos($text2,",")-1);
//$text2=str_replace("\\\"","\"",$text2);


$text="";
if (strpos($ex[$i],"\"visual_descriptions\":")!==false) {
$text=substr($ex[$i],strpos($ex[$i],"\"visual_descriptions\":")+22);
$text=substr($text,0,strpos($text,"]")-1);
if (strpos($text,"]")!==FALSE && strrpos($text,"]")==0) 
{$text="";} 
else {$text=substr($text,1);}
if (strpos($text,"\",\"")!==FALSE) {$text=substr($text,0,strpos($text,"\",\""));}
if (strpos($text,"\"")!==False && strpos($text,"\"")==0) {$text=substr($text,1);}

}
//$text=str_replace("\\","\\\\",$text);
$text=str_replace("\\\"","\"",$text);

if (strpos($ex[$i],",\"seo_description\":")!==FALSE)
{
$seo=substr($ex[$i],strpos($ex[$i],",\"seo_description\":")+19);
if (strpos($seo,"}")!==FALSE && (strpos($seo,"}")<strpos($seo,"\",")))
{$seo=substr($seo,0,strpos($seo,"}")-1);}
else {$seo=substr($seo,0,strpos($seo,"\",")-1);}
if (strpos($seo,"\"")!==False && strpos($seo,"\"")==0) {$seo=substr($seo,1);}
if ($seo==="}}") {$seo="";}
if (strpos($seo,"}}")>strlen($seo)-3) {$seo=substr($seo,strlen($seo)-2);}
//echo "<br>seotest:".strlen($seo);
//var_dump($seo);
}

if (strlen($title)==0){$title=$seo;}
//if ($title==="seo") {$title="";}
if (strlen($title)==0){$title=$text;}
//if ($title==="text") {$title="";}

if (strpos($title,"\",")!==FALSE && strpos($title,"\",")==0) {
$seo=$text;
$title=$text;
}
if (strlen($title)==0){$title=$text2;}
if ((strpos($title,"\",")!==FALSE && strpos($title,"\",")==0) || strpos($title,",\"")!==FALSE && strpos($title,",\"")==0) {
$seo=$text2;
$title=$text2;
$text=$text2;
}



$isig=substr($ex[$i],strpos($ex[$i],",\"image_signature\":")+20);
$isig=substr($isig,0,strpos($isig,",")-1);
$ih=substr($ex[$i],strpos($ex[$i],"\"images\":{")+10);
$ih=substr($ih,0,strpos($ih,"}}"));
//echo $ih;
$ex2=explode("{",$ih);
$ih2=$ex[1];
$ih2=substr($ex[$i],strpos($ex[$i],",\"height\":")+10);
$ih2=substr($ih2,0,strpos($ih2,","));
$links="";
if (strpos($ex[$i],",\"annotations_with_links\":{")!==FALSE) {
$links=substr($ex[$i],strpos($ex[$i],",\"annotations_with_links\":{")+27);
$links=substr($links,0,strpos($links,"}}"));
} 
else if (strpos($ex[$i],"{\"annotations_with_links\":{")!==FALSE) {
$links=substr($ex[$i],strpos($ex[$i],"{\"annotations_with_links\":{")+27);
$links=substr($links,0,strpos($links,"}}"));
}
else {
$links=substr($ex[$i],strpos($ex[$i],",\"annotations_with_links\":")+27);
$links=substr($links,0,strpos($links,"}}"));

//echo $ex[$i]."\n\n<br><br>".$json;
}
//echo "hello world";
$ih3=substr($ih,strpos($ih,":236,"));
//echo "<br>new<br>".$ih3;
$ih3=substr($ih3,strpos($ih3,",\"height\":")+10);
//echo "<br><br>".$ih3;
$ih3=substr($ih3,0,strpos($ih3,","));
$ex2=explode(",\"url\":\"",$ih);
//echo count($ex2);

//if (count($ex2)<=2) {continue;}
$count++;
$top=0;

$jump=109;
if (strlen($text)>70) {$jump+=16;}
if ($top0<=$top1 && $top0<=$top2 && $top0<=$top3) {$left=0;$top=$top0;$top0+=$ih3+$jump;}
else if ($top1<=$top2 && $top1<=$top3) {$left=250;$top=$top1;$top1+=$ih3+$jump;}
else if ($top2<=$top3) {$left=500;$top=$top2;$top2+=$ih3+$jump;}
else {$left=750;$top=$top3;$top3+=$ih3+$jump;}
//echo "<br><br>".$ih3;

$insert.="
<div class=\"Grid__Item\" style=\"top: ".$top."px; left: ".$left."px;\">
<div class=\"PinGridInner__brioPin GrowthUnauthPin_brioPin\" data-test-id=\"pin\" data-test-pin-id=\"".$id."\" role=\"button\" tabindex=\"0\" style=\"width: 236px;\">
<div class=\"GrowthUnauthPinImage\">
<a href=\"/pin/".$id."/\" target=\"_self\" title=\" \" style=\"background: rgb(182, 182, 181); max-height: 500px;\">";
$ex2=explode(",\"url\":\"",$ih);
//echo "<br>".$ih;
//echo "<br><br>".$ex2[1];
//echo "<br><br>".substr($ex2[1],0,strpos($ex2[1],"}")-1);
$text3=substr($text,0,strpos($text,"<"));
$text3=substr($text,0,strpos($text,"\""));

$insert.="<img alt=\"".$text."\" class=\"GrowthUnauthPinImage__Image\" src=\"";
$insert.=substr($ex2[1],0,strpos($ex2[1],"}")-2);
$insert.="\" srcset=\"".substr($ex2[2],0,strpos($ex2[2],"}")-1)." 1x, ";
$insert.=substr($ex2[3],0,strpos($ex2[3],"}")-1);
$insert.=" 2x\" style=\"height: ";
$insert.=substr($ex2[2],0,strpos($ex2[2],"}")-1);
$insert.="px;\"></a></div>
<figcaption>
<h3 class=\"PinDescription__desc PinDescription__2LineDesc\" data-test-id=\"desc\">".$title."</h3>
</figcaption>
<div class=\"vaseCarousel_vasetags_container\" data-test-id=\"vasetags\" style=\"height: 30px;\">
<div class=\"vaseCarousel_vasetags_wrapper\">";
$ex2=explode("}",$links);
$y=count($ex2);
//if ($y==1) {echo $link."<br><br>".$aa."<br>".$json;}

for ($j=0;$j<$y;$j++) {
$aa=substr($ex2[$j],strpos($ex2[$j],"\"url\":")+7);  $aa=substr($aa,0,strpos($aa,","));
if (substr($ex2[$j],0,strpos($ex2[$j],"}")!==false)){
$ab=substr($ex2[$j],strpos($ex2[$j],",\"name\":")+9);$ab=substr($ab,0,strpos($ab,"}"));}
else {$ab=substr($ex2[$j],strpos($ex2[$j],",\"name\":")+9);$ab=substr($ab,0,strpos($ab,"\""));}
$insert.="<a href=\"".$aa."\" class=\"vaseCarousel_vaseTagLink\" target=\"_self\">".$ab."</a>";
}//endfor
$insert.="<a href=\"\" class=\"VaseCarousel__button VaseCarousel__buttonRight\">
<svg class=\"Hn_ gUZ B9u\" height=\"8\" width=\"8\" viewBox=\"0 0 24 24\" aria-label=\"Forward\" role=\"img\">
<path d=\"M6.72 24c.57 0 1.14-.22 1.57-.66L19.5 12 8.29.66c-.86-.88-2.27-.88-3.14 0-.87.88-.87 2.3 0 3.18L13.21 12l-8.06 8.16c-.87.88-.87 2.3 0 3.18.43.44 1 .66 1.57.66\"></path></svg></a></div></div>";
if (strlen($text)>0) {
$insert.="<p class=\"PinDescription__desc PinDescription__2LineDesc\" data-test-id=\"desc\">".(strlen($text)>70)?substr($text,0,70)."...":$text."</p>";}
if (strlen($text)>70) {
$insert.="<button data-test-id=\"seemoretoggle\" class=\"PinDescriptionSeeMore_summary underlineLink\" style=\"background-color: transparent; border: 0px; color: rgb(51, 51, 51); font-size: 12px; font-weight: bold; display: flex; justify-content: flex-end; height: 15px; margin: 0px 0px 3px; outline: none; padding: 0px; -webkit-font-smoothing: antialiased;\">See more
<span style=\"margin-left: 4px;\">
<svg class=\"gUZ B9u\" height=\"10\" width=\"10\" viewBox=\"0 0 24 24\" aria-label=\"expand\" role=\"img\">
<path d=\"M12 19.5L.66 8.29c-.88-.86-.88-2.27 0-3.14.88-.87 2.3-.87 3.18 0L12 13.21l8.16-8.06c.88-.87 2.3-.87 3.18 0 .88.87.88 2.28 0 3.14L12 19.5z\"></path></svg></span></button>";
}
$insert.="</div></div>";

}//endfor

//if ($count<2) {echo "no results".$json;}


$insert.="</div></div></main></div><header data-test-id=\"unauthHeader\" id=\"HeaderContent\" style=\"top: 0px; background-color: rgb(255, 255, 255); border-bottom: 1px solid rgb(204, 204, 204); width: 100%; z-index: 675; position: absolute;\"><div style=\"height: 64px; display: flex; align-items: center; margin: 0px 16px;\">
<div style=\"order: 1;\">
<div style=\"display: inline-flex;\">
<div class=\"UnauthHeader__rightContentContainer\" style=\"position: relative;\">
<div data-test-id=\"signupButton\" class=\"zI7 iyn Hsu\"><button aria-label=\"\" class=\"red headerSignupButton active\" type=\"button\" style=\"border: 0px; height: 40px; display: inline-block; border-radius: 4px; -webkit-font-smoothing: antialiased; padding: 0px; font-size: 14px; font-weight: bold; outline: none; box-shadow: none; cursor: pointer; margin-top: 0px; vertical-align: middle; text-align: center; background-color: rgb(230, 0, 35); color: rgb(255, 255, 255); line-height: 36px; width: 128px; margin-right: 8px;\">Sign up</button></div>
<div data-test-id=\"loginButton\" class=\"lightGrey headerLoginButton active\"><button aria-label=\"\" class=\"lightGrey headerLoginButton active\" type=\"button\" style=\"border: 0px; height: 40px; display: inline-block; border-radius: 4px; -webkit-font-smoothing: antialiased; padding: 0px; font-size: 14px; font-weight: bold; outline: none; box-shadow: none; cursor: pointer; margin-top: 0px; vertical-align: middle; text-align: center; background-color: rgb(235, 235, 235); color: rgb(68, 68, 68); width: 128px;\">Log in</button></div>
<div><div class=\"hDW mQ8 n9m zI7 iyn Hsu\">
<button aria-label=\"Setting button\" class=\"rYa kVc adn yQo qrs BG7\" type=\"button\">
<div class=\"x8f INd _O1 gjz mQ8 OGJ YbY\" style=\"height: 32px; width: 32px;\">
<div class=\"INd zI7 iyn Hsu\">
<svg class=\"gUZ pBj U9O kVc\" height=\"16\" width=\"16\" viewBox=\"0 0 24 24\" aria-hidden=\"true\" aria-label=\"\" role=\"img\"><path d=\"M12 9c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3M3 9c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm18 0c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3z\"></path></svg></div></div></button>
</div></div></div></div></div><div style=\"display: flex; flex: 1 1 0%; align-items: center;\"><a class=\"Wk9 xQ4 WMU iyn ljY kVc\" href=\"/\"><span style=\"cursor: pointer; display: block; float: left; height: 28px; width: 28px; vertical-align: middle;\">
<svg height=\"28\" viewBox=\"3 3 70 70\" width=\"28\" style=\"display: block;\"><path d=\"M27.5 71c3.3 1 6.7 1.6 10.3 1.6C57 72.6 72.6 57 72.6 37.8 72.6 18.6 57 3 37.8 3 18.6 3 3 18.6 3 37.8c0 14.8 9.3 27.5 22.4 32.5-.3-2.7-.6-7.2 0-10.3l4-17.2s-1-2-1-5.2c0-4.8 3-8.4 6.4-8.4 3 0 4.4 2.2 4.4 5 0 3-2 7.3-3 11.4C35.6 49 38 52 41.5 52c6.2 0 11-6.6 11-16 0-8.3-6-14-14.6-14-9.8 0-15.6 7.3-15.6 15 0 3 1 6 2.6 8 .3.2.3.5.2 1l-1 3.8c0 .6-.4.8-1 .4-4.4-2-7-8.3-7-13.4 0-11 7.8-21 22.8-21 12 0 21.3 8.6 21.3 20 0 12-7.4 21.6-18 21.6-3.4 0-6.7-1.8-7.8-4L32 61.7c-.8 3-3 7-4.5 9.4z\" fill=\"#e60023\" fill-rule=\"evenodd\"></path></svg></span></a><a href=\"/\" role=\"button\" style=\"text-decoration: none;\"><span class=\"UnauthHeader__discoverText\" style=\"color: rgb(67, 67, 67); font-size: 18px; font-weight: bold; margin-left: 14px; -webkit-font-smoothing: antialiased;\">Pinterest</span></a><div style=\"flex: 1 1 0%;\">";

$insert.=$sty4;

$insert.="<div class=\"OpenSearchForm\" style=\"box-sizing: border-box; display: block; float: none; height: auto; margin-left: 16px; margin-right: 16px; position: relative; text-align: left; width: auto;\"><form name=\"search\"><div class=\"typeaheadField guided\" style=\"display: block; float: none; position: relative; width: 100%;\"><div class=\"tokenizedInputWrapper\" role=\"search\" style=\"background-color: rgb(239, 239, 239); border-radius: 4px; box-sizing: border-box; height: 40px; position: relative; display: flex; align-items: center;\"><em></em><div style=\"margin-left: 16px;\"><svg class=\"gUZ B9u U9O kVc\" height=\"20\" width=\"20\" viewBox=\"0 0 24 24\" aria-label=\"search\" role=\"img\"><path d=\"M10 16c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6m13.12 2.88l-4.26-4.26A9.842 9.842 0 0 0 20 10c0-5.52-4.48-10-10-10S0 4.48 0 10s4.48 10 10 10c1.67 0 3.24-.41 4.62-1.14l4.26 4.26a3 3 0 0 0 4.24 0 3 3 0 0 0 0-4.24\"></path></svg></div><div class=\"tokenizedInput guided typeaheadWithTitles\" style=\"margin-left: 11px; overflow: hidden; flex: 1 1 0%; display: flex; align-items: center;\"><div class=\"scrollWrapper\" style=\"overflow: hidden; flex: 1 1 0%;\"><ul class=\"tokensWrapper\" style=\"cursor: text; float: left; min-height: 1px; white-space: nowrap; width: 100%; left: 0px; overflow: visible; position: relative; transition-duration: 0.25s; transition-property: left;\"><li class=\"tokenizedItem inputToken\" style=\"color: rgb(0, 0, 0); display: inline-block; margin: 0px; overflow: hidden; width: 100%; white-space: nowrap; vertical-align: middle;\">
<label><div class=\"NVN zI7 iyn Hsu\">Search</div><input autocomplete=\"off\" class=\"OpenSearchBoxInput\" name=\"q\" placeholder=\"Search for easy dinners, fashion, etc.\" type=\"text\" value=\"\" style=\"background: transparent; border: 0px; border-radius: 3px; box-shadow: none; box-sizing: border-box; font-size: 16px; font-weight: 600; height: 40px; line-height: 20px; overflow: hidden; width: 100%;\"></label></li></ul></div></div></div><div><div class=\"OpenTypeahead guided typeaheadWithTitles\" style=\"margin: 41px 0px 0px; position: absolute; border-radius: 0px 0px 6px 6px; top: 0px;\"><ul class=\"results\"></ul></div></div></div></form></div></div></div></div></header><div><div data-test-id=\"giftWrap\" role=\"dialog\" style=\"background: rgba(0, 0, 0, 0.65); transition: height 0.5s cubic-bezier(0.26, 0.87, 0.74, 0.93) 0s; backface-visibility: hidden; bottom: 0px; color: rgb(255, 255, 255); height: 0px; position: fixed; width: 100%;\"><div data-test-id=\"quarterBanner\" class=\"gridCentered\" style=\"transition: opacity 0.5s linear 0s; pointer-events: none; visibility: hidden; opacity: 0; height: 0px;\"><div class=\"Jea b8T gjz zI7 iyn Hsu\" style=\"height: 64px; width: 100%;\"><div style=\"color: rgb(255, 255, 255); display: inline-block; font-size: 24px; font-weight: 500; vertical-align: middle; font-style: normal; font-stretch: normal; line-height: normal; text-align: left;\">Explore more ideas with a Pinterest account</div><div class=\"Jea b8T hA- wYR zI7 iyn Hsu\">
<button aria-label=\"\" class=\"white active\" type=\"button\" style=\"border: 0px; height: 44px; display: inline-block; border-radius: 4px; -webkit-font-smoothing: antialiased; padding: 0px 14px; font-size: 16px; font-weight: bold; outline: none; box-shadow: none; cursor: pointer; margin-top: 0px; vertical-align: middle; text-align: center; background-color: rgb(255, 255, 255); color: rgb(68, 68, 68); line-height: 36px; min-width: 128px; margin-right: 5px; white-space: nowrap;\">Sign up</button><button aria-label=\"\" class=\"darkGrey active\" type=\"button\" style=\"border: 1px solid rgb(255, 255, 255); height: 44px; display: inline-block; border-radius: 4px; -webkit-font-smoothing: antialiased; padding: 0px 14px; font-size: 16px; font-weight: bold; outline: none; box-shadow: none; cursor: pointer; margin-top: 0px; vertical-align: middle; text-align: center; background-color: transparent; color: rgb(255, 255, 255); line-height: 36px; min-width: 128px; margin-right: 5px;\">Log in</button></div></div></div><div data-test-id=\"fullBanner\" role=\"dialog\" style=\"transition: opacity 0.5s linear 0s; pointer-events: none; visibility: hidden; opacity: 0; height: 0px;\"><div role=\"dialog\" style=\"pointer-events: none; visibility: hidden; opacity: 0;\">
<div data-test-id=\"signup\" style=\"background-color: rgb(255, 255, 255); border-radius: 8px; position: relative; text-align: center; width: 484px; margin: auto; min-height: 450px; box-shadow: rgba(0, 0, 0, 0.45) 0px 2px 10px;\"><div style=\"min-height: 400px; padding: 20px 10px 24px;\"><div style=\"display: block; height: 45px; margin: 5px auto 8px; width: 45px;\"><svg height=\"48\" viewBox=\"-3 -3 82 82\" width=\"48\" style=\"display: block;\"><circle cx=\"38\" cy=\"38\" fill=\"white\" r=\"40\"></circle><path d=\"M27.5 71c3.3 1 6.7 1.6 10.3 1.6C57 72.6 72.6 57 72.6 37.8 72.6 18.6 57 3 37.8 3 18.6 3 3 18.6 3 37.8c0 14.8 9.3 27.5 22.4 32.5-.3-2.7-.6-7.2 0-10.3l4-17.2s-1-2-1-5.2c0-4.8 3-8.4 6.4-8.4 3 0 4.4 2.2 4.4 5 0 3-2 7.3-3 11.4C35.6 49 38 52 41.5 52c6.2 0 11-6.6 11-16 0-8.3-6-14-14.6-14-9.8 0-15.6 7.3-15.6 15 0 3 1 6 2.6 8 .3.2.3.5.2 1l-1 3.8c0 .6-.4.8-1 .4-4.4-2-7-8.3-7-13.4 0-11 7.8-21 22.8-21 12 0 21.3 8.6 21.3 20 0 12-7.4 21.6-18 21.6-3.4 0-6.7-1.8-7.8-4L32 61.7c-.8 3-3 7-4.5 9.4z\" fill=\"#e60023\" fill-rule=\"evenodd\"></path></svg></div>
<div style=\"margin: 0px auto 18px; width: 400px;\"></div><div style=\"margin: 0px auto 18px; width: 270px;\">
<h3 style=\"text-align: center; color: rgb(51, 51, 51); font-size: 16px; font-weight: normal; margin: -15px 0px 32px;\">Sign up to see more</h3></div>
<div data-test-id=\"signup\" style=\"margin: 0px auto; position: relative; text-align: center;\"><div style=\"margin: 0px auto; width: 268px;\"><div><div data-test-id=\"emailSignUpButton\"><button class=\"RCK Hsu mix Vxj aZc GmH adn Il7 Jrn hNT iyn BG7 gn8 L4E kVc\" type=\"button\"><div class=\"tBJ dyH iFc SMy yTZ erh tg7 mWe\">Continue with email</div></button></div><div style=\"margin-top: 10px;\"><div style=\"position: relative;\"><div><button aria-label=\"\" class=\"FacebookConnectButton active\" type=\"button\" style=\"border: 0px; height: 40px; display: block; border-radius: 8px; -webkit-font-smoothing: antialiased; padding: 0px 0px 0px 8px; font-size: 15px; font-weight: normal; outline: none; box-shadow: none; cursor: pointer; margin-top: 0px; vertical-align: middle; text-align: left; background-clip: padding-box; background-color: rgb(24, 119, 242); position: absolute; transition: opacity 0.2s linear 0s; width: 100%;\"><svg class=\"gUZ erh\" height=\"24\" width=\"24\" viewBox=\"0 0 24 24\" aria-label=\"facebook\" role=\"img\"><path d=\"M17.75 3.984l-2.312.001c-1.811 0-2.163.842-2.163 2.077v2.724h4.323l-.563 4.267h-3.76V24H8.769V13.053H5V8.786h3.769V5.64C8.769 1.988 11.05 0 14.383 0c1.596 0 2.967.116 3.367.168v3.816z\"></path></svg>
<span style=\"color: rgb(255, 255, 255); display: inline-block; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen-Sans, Ubuntu, Cantarell, &quot;Fira Sans&quot;, &quot;Droid Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;ヒラギノ角ゴ Pro W3&quot;, &quot;Hiragino Kaku Gothic Pro&quot;, メイリオ, Meiryo, &quot;ＭＳ Ｐゴシック&quot;, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;; font-size: 15px; font-weight: bold; letter-spacing: normal; line-height: 16px; padding-left: 4px; padding-top: 1px; text-align: center; vertical-align: text-bottom; -webkit-font-smoothing: auto; white-space: normal; width: 88%;\">Continue with Facebook</span></button><div style=\"height: 40px; text-align: left;\">";

$insert.="<div class=\"fb-login-button fb_iframe_widget\" data-scope=\"public_profile,email,user_likes,user_birthday,user_friends\" onlogin=\"checkLoginState\" data-button-type=\"continue_with\" data-use-continue-as=\"true\" data-width=\"268px\" data-size=\"large\" login_text=\"\" fb-xfbml-state=\"rendered\" fb-iframe-plugin-query=\"app_id=274266067164&amp;button_type=continue_with&amp;container_width=268&amp;locale=en_US&amp;scope=public_profile%2Cemail%2Cuser_likes%2Cuser_birthday%2Cuser_friends&amp;sdk=joey&amp;size=large&amp;use_continue_as=true&amp;width=268px\">
<span style=\"vertical-align: bottom; width: 268px; height: 40px;\">
<iframe name=\"fa3f6c92e1d38\" height=\"1000px\" title=\"fb:login_button Facebook Social Plugin\" frameborder=\"0\" allowtransparency=\"true\" allowfullscreen=\"true\" scrolling=\"no\" allow=\"encrypted-media\" src=\"https://www.facebook.com/v2.2/plugins/login_button.php?app_id=274266067164&amp;button_type=continue_with&amp;channel=https%3A%2F%2Fstaticxx.facebook.com%2Fconnect%2Fxd_arbiter.php%3Fversion%3D45%23cb%3Dff7e81d3340dbc%26domain%3Dwww.pinterest.com%26origin%3Dhttps%253A%252F%252Fwww.pinterest.com%252Ff188989a052b908%26relation%3Dparent.parent&amp;container_width=268&amp;locale=en_US&amp;scope=public_profile%2Cemail%2Cuser_likes%2Cuser_birthday%2Cuser_friends&amp;sdk=joey&amp;size=large&amp;use_continue_as=true&amp;width=268px\" style=\"border: none; visibility: visible; width: 268px; height: 40px;\" class=\"\"></iframe>
</span>
</div></div></div></div><div style=\"height: 10px;\"></div><div>
<button aria-label=\"\" class=\"GoogleConnectButton active\" id=\"googleConnectButton\" type=\"button\" style=\"border: 0px; height: 40px; display: block; border-radius: 4px; -webkit-font-smoothing: antialiased; padding: 1px; font-size: 15px; font-weight: bold; outline: none; box-shadow: none; cursor: pointer; margin-top: 0px; vertical-align: middle; text-align: left; background-color: rgb(239, 239, 239); margin-right: 0px; background-clip: padding-box; transition: opacity 0.2s linear 0s; position: relative; width: 100%;\"><div style=\"background-color: transparent; border-radius: 2px; display: inline-block; height: 24px; margin-left: 12px; text-align: center; width: 24px;\"><div style=\"position: relative; margin: 2px auto auto;\"><svg height=\"20\" viewBox=\"0 0 512 512\" width=\"20\" xmlns=\"http://www.w3.org/2000/svg\"><g fill=\"none\" fill-rule=\"evenodd\"><path d=\"M482.56 261.36c0-16.73-1.5-32.83-4.29-48.27H256v91.29h127.01c-5.47 29.5-22.1 54.49-47.09 71.23v59.21h76.27c44.63-41.09 70.37-101.59 70.37-173.46z\" fill=\"#4285f4\"></path><path d=\"M256 492c63.72 0 117.14-21.13 156.19-57.18l-76.27-59.21c-21.13 14.16-48.17 22.53-79.92 22.53-61.47 0-113.49-41.51-132.05-97.3H45.1v61.15c38.83 77.13 118.64 130.01 210.9 130.01z\" fill=\"#34a853\"></path><path d=\"M123.95 300.84c-4.72-14.16-7.4-29.29-7.4-44.84s2.68-30.68 7.4-44.84V150.01H45.1C29.12 181.87 20 217.92 20 256c0 38.08 9.12 74.13 25.1 105.99l78.85-61.15z\" fill=\"#fbbc05\"></path><path d=\"M256 113.86c34.65 0 65.76 11.91 90.22 35.29l67.69-67.69C373.03 43.39 319.61 20 256 20c-92.25 0-172.07 52.89-210.9 130.01l78.85 61.15c18.56-55.78 70.59-97.3 132.05-97.3z\" fill=\"#ea4335\"></path><path d=\"M20 20h472v472H20V20z\"></path></g></svg></div></div><span style=\"color: rgb(51, 51, 51); display: inline-block; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen-Sans, Ubuntu, Cantarell, &quot;Fira Sans&quot;, &quot;Droid Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;ヒラギノ角ゴ Pro W3&quot;, &quot;Hiragino Kaku Gothic Pro&quot;, メイリオ, Meiryo, &quot;ＭＳ Ｐゴシック&quot;, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;; position: absolute; text-align: center; top: 50%; font-size: 16px; transform: translateY(-50%) translateX(-37px); vertical-align: top; -webkit-font-smoothing: auto; width: 100%;\">Continue with Google</span></button>
</div>";
$insert.="</div></div><div style=\"margin-top: 16px;\"><span class=\"\" style=\"-webkit-font-smoothing: antialiased; font-size: 11px; font-weight: normal; text-align: center; transition: opacity 0.2s linear 0s; color: rgb(142, 142, 142); width: 224px;\"><span>By continuing, you agree to Pinterest's <a data-test-id=\"tos\" href=\"/_/_/about/terms-service/\" target=\"_blank\">Terms of Service</a>, <a data-test-id=\"privacy\" href=\"/_/_/about/privacy/\" target=\"_blank\">Privacy Policy</a></span></span></div><div><div style=\"border-bottom: 1px solid rgb(239, 239, 239); margin: 20px 0px 15px -68px; width: 404px;\"></div><div><div style=\"margin: 0px auto 5px; width: fit-content; align-items: baseline;\"><div class=\"zI7 iyn Hsu\"><a style=\"color: rgb(51, 51, 51); cursor: pointer; margin-left: 5px;\">Already a member? Log in</a></div></div></div></div></div></div></div></div></div></div></div></div><button aria-label=\"\" class=\"noButtonStyles active\" type=\"button\" style=\"background: none; border: none; padding: 0px; text-align: left;\"><div style=\"background-color: rgb(255, 255, 255); border-radius: 4px; box-shadow: rgba(0, 0, 0, 0.1) 0px 2px 0px 0px, rgba(0, 0, 0, 0.04) 0px 0px 0px 0.5px; color: rgb(51, 51, 51); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen-Sans, Ubuntu, Cantarell, &quot;Fira Sans&quot;, &quot;Droid Sans&quot;, &quot;Helvetica Neue Bold&quot;, Helvetica, &quot;ヒラギノ角ゴ Pro W3&quot;, &quot;Hiragino Kaku Gothic Pro&quot;, メイリオ, Meiryo, &quot;ＭＳ Ｐゴシック&quot;, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;; font-size: 12px; font-weight: normal; padding: 2px 5px 3px; position: fixed; right: 10px; bottom: 80px;\">Privacy</div></button></div></div></div><div style=\"bottom: 14px; left: 50%; margin-left: -183px; position: fixed; z-index: 1500;\"></div></div>";

$json=substr($json,strpos($json,"{"));
//$json="<script id=\"initial-state\" type=\"application/json\">"."\n//".$json."\n</script>";
$r=$pre.$insert.$post;

}//end else if

else if ($a==="facebook.com") {
if (strpos($c,"<div class=\"_5hn6\" id=\"u_0_f\"") && strpos($c,"<div class=\"clearfix\" id=\"content_container\">")) {
$pre=substr($c,0,strpos($c,"<div class=\"_5hn6\" id=\"u_0_f\""));
$post=substr($c,strpos($c,"<div class=\"clearfix\" id=\"content_container\">"));
}
else {
//some mechanism to alert facebook has changed.
//$r=$c;
}

$r=$pre.$post;
}

//$r.=$r;
return $r;
}//endfunction

//for individual fixing/debugging uncomment what you need
//gab
//echo template("gab.com","/RealAlexJones/posts/103524882982132197","{\"id\":\"103524882982132197\",\"created_at\":\"2020-01-22T03:39:12.198Z\",\"revised_at\":null,\"in_reply_to_id\":null,\"in_reply_to_account_id\":null,\"sensitive\":false,\"spoiler_text\":\"\",\"visibility\":\"public\",\"language\":\"en\",\"uri\":\"https://gab.com/users/RealAlexJones/statuses/103524882982132197\",\"url\":\"https://gab.com/RealAlexJones/posts/103524882982132197\",\"replies_count\":3,\"reblogs_count\":11,\"favourites_count\":15,\"quote_of_id\":null,\"favourited\":false,\"reblogged\":false,\"muted\":false,\"content\":\"\u003cp\u003eMainstream media narrative completely destroyed!\u003c/p\u003e\u003cp\u003e\u003ca href=\\\"https://www.infowars.com/do-i-look-like-a-white-supremacist/\\\" rel=\\\"nofollow noopener\\\" target=\\\"_blank\\\"\u003e\u003cspan class=\\\"invisible\\\"\u003ehttps://www.\u003c/span\u003e\u003cspan class=\\\"ellipsis\\\"\u003einfowars.com/do-i-look-like-a-\u003c/span\u003e\u003cspan class=\\\"invisible\\\"\u003ewhite-supremacist/\u003c/span\u003e\u003c/a\u003e\u003c/p\u003e\",\"reblog\":null,\"quote\":null,\"application\":{\"name\":\"Web\",\"website\":null},\"account\":{\"id\":\"76559\",\"username\":\"RealAlexJones\",\"acct\":\"RealAlexJones\",\"display_name\":\"Alex Jones\",\"locked\":false,\"bot\":false,\"created_at\":\"2016-11-21T18:37:57.000Z\",\"note\":\"\u003cp\u003eThe MOST BANNED person on the internet! 🚫\u003c/p\u003e\u003cp\u003eBanned by Facebook, Twitter, Spotify, instagram, iHeartRadio, TuneIn, Stitcher, Apple, SproutSocial, Audioboom, Pinterest, Vimeo, Youtube, Tumblr, Reddit, Flickr, Periscope, Linkedin, GooglePlay, iTunes, Dlive, PayPal, Patreon, Mastodon, Vero...\u003c/p\u003e\u003cp\u003e🆕🆕🆕 \u003ca href=\\\"https://BANNED.video\\\" rel=\\\"nofollow noopener\\\" target=\\\"_blank\\\"\u003e\u003cspan class=\\\"invisible\\\"\u003ehttps://\u003c/span\u003e\u003cspan class=\\\"\\\"\u003eBANNED.video\u003c/span\u003e\u003cspan class=\\\"invisible\\\"\u003e\u003c/span\u003e\u003c/a\u003e\u003c/p\u003e\u003cp\u003e📡Tune in 247: \u003ca href=\\\"https://infowars.com/show\\\" rel=\\\"nofollow noopener\\\" target=\\\"_blank\\\"\u003e\u003cspan class=\\\"invisible\\\"\u003ehttps://\u003c/span\u003e\u003cspan class=\\\"\\\"\u003einfowars.com/show\u003c/span\u003e\u003cspan class=\\\"invisible\\\"\u003e\u003c/span\u003e\u003c/a\u003e\u003c/p\u003e\u003cp\u003e📰Newsletter: \u003ca href=\\\"https://infowars.com/newsletter\\\" rel=\\\"nofollow noopener\\\" target=\\\"_blank\\\"\u003e\u003cspan class=\\\"invisible\\\"\u003ehttps://\u003c/span\u003e\u003cspan class=\\\"\\\"\u003einfowars.com/newsletter\u003c/span\u003e\u003cspan class=\\\"invisible\\\"\u003e\u003c/span\u003e\u003c/a\u003e\u003c/p\u003e\u003cp\u003e🛒Support: \u003ca href=\\\"https://infowarsstore.com/\\\" rel=\\\"nofollow noopener\\\" target=\\\"_blank\\\"\u003e\u003cspan class=\\\"invisible\\\"\u003ehttps://\u003c/span\u003e\u003cspan class=\\\"\\\"\u003einfowarsstore.com/\u003c/span\u003e\u003cspan class=\\\"invisible\\\"\u003e\u003c/span\u003e\u003c/a\u003e\u003c/p\u003e\u003cp\u003e⭐SubscribeStar: \u003ca href=\\\"https://subscribestar.com/alexjones\\\" rel=\\\"nofollow noopener\\\" target=\\\"_blank\\\"\u003e\u003cspan class=\\\"invisible\\\"\u003ehttps://\u003c/span\u003e\u003cspan class=\\\"\\\"\u003esubscribestar.com/alexjones\u003c/span\u003e\u003cspan class=\\\"invisible\\\"\u003e\u003c/span\u003e\u003c/a\u003e\u003c/p\u003e\",\"url\":\"https://gab.com/RealAlexJones\",\"avatar\":\"https://gab.com/system/accounts/avatars/000/076/559/original/087a1b6017e25272.jpg?1565823644\",\"avatar_static\":\"https://gab.com/system/accounts/avatars/000/076/559/original/087a1b6017e25272.jpg?1565823644\",\"header\":\"https://gab.com/media/user/5b7ad6f188e51.jpeg\",\"header_static\":\"https://gab.com/media/user/5b7ad6f188e51.jpeg\",\"followers_count\":71089,\"following_count\":16,\"statuses_count\":11577,\"is_pro\":false,\"is_verified\":true,\"is_donor\":false,\"is_investor\":false,\"emojis\":[],\"fields\":[]},\"group\":null,\"media_attachments\":[],\"mentions\":[],\"tags\":[],\"emojis\":[],\"card\":{\"url\":\"https://www.infowars.com/do-i-look-like-a-white-supremacist/\",\"title\":\"Do I Look Like a White Supremacist?\",\"description\":\"Mainstream media narrative completely destroyed\",\"type\":\"link\",\"author_name\":\"Kelen McBreen\",\"author_url\":\"https://www.infowars.com/author/kelen-mcbreen/\",\"provider_name\":\"\",\"provider_url\":\"https://www.infowars.com\",\"html\":\"\",\"width\":400,\"height\":210,\"image\":\"https://gab.com/system/preview_cards/images/003/259/854/original/93e34daca215d2ce.jpeg?1579651670\",\"embed_url\":\"\"},\"poll\":null}","{\"ancestors\":[],\"descendants\":[{\"id\":\"103524927878687312\",\"created_at\":\"2020-01-22T03:50:37.275Z\",\"revised_at\":null,\"in_reply_to_id\":\"103524882982132197\",\"in_reply_to_account_id\":\"76559\",\"sensitive\":false,\"spoiler_text\":\"\",\"visibility\":\"public\",\"language\":\"en\",\"uri\":\"https://gab.com/users/Bobbala/statuses/103524927878687312\",\"url\":\"https://gab.com/Bobbala/posts/103524927878687312\",\"replies_count\":0,\"reblogs_count\":0,\"favourites_count\":0,\"quote_of_id\":null,\"favourited\":false,\"reblogged\":false,\"muted\":false,\"content\":\"\u003cp\u003eWould she settle for anything less than nazi oppression ... probably not.\u003c/p\u003e\u003cp\u003e\u003cspan class=\\\"h-card\\\"\u003e\u003ca href=\\\"https://gab.com/RealAlexJones\\\" class=\\\"u-url mention\\\"\u003e@\u003cspan\u003eRealAlexJones\u003c/span\u003e\u003c/a\u003e\u003c/span\u003e\u003c/p\u003e\",\"reblog\":null,\"quote\":null,\"application\":{\"name\":\"Web\",\"website\":null},\"account\":{\"id\":\"57238\",\"username\":\"Bobbala\",\"acct\":\"Bobbala\",\"display_name\":\"Bob\",\"locked\":false,\"bot\":false,\"created_at\":\"2016-11-17T00:15:08.000Z\",\"note\":\"\u003cp\u003ePeople with truth on their side don\u0026apos;t fear words ...\u003c/p\u003e\",\"url\":\"https://gab.com/Bobbala\",\"avatar\":\"https://gab.com/media/user/58312a36388cd.png\",\"avatar_static\":\"https://gab.com/media/user/58312a36388cd.png\",\"header\":\"https://gab.com/headers/original/missing.png\",\"header_static\":\"https://gab.com/headers/original/missing.png\",\"followers_count\":618,\"following_count\":159,\"statuses_count\":11145,\"is_pro\":false,\"is_verified\":false,\"is_donor\":false,\"is_investor\":false,\"emojis\":[],\"fields\":[]},\"group\":null,\"media_attachments\":[],\"mentions\":[{\"id\":\"76559\",\"username\":\"RealAlexJones\",\"url\":\"https://gab.com/RealAlexJones\",\"acct\":\"RealAlexJones\"}],\"tags\":[],\"emojis\":[],\"card\":null,\"poll\":null},{\"id\":\"103524990046017137\",\"created_at\":\"2020-01-22T04:06:25.865Z\",\"revised_at\":null,\"in_reply_to_id\":\"103524882982132197\",\"in_reply_to_account_id\":\"76559\",\"sensitive\":false,\"spoiler_text\":\"\",\"visibility\":\"public\",\"language\":\"en\",\"uri\":\"https://gab.com/users/PatDollard/statuses/103524990046017137\",\"url\":\"https://gab.com/PatDollard/posts/103524990046017137\",\"replies_count\":1,\"reblogs_count\":0,\"favourites_count\":0,\"quote_of_id\":null,\"favourited\":false,\"reblogged\":false,\"muted\":false,\"content\":\"\u003cp\u003e\u003cspan class=\\\"h-card\\\"\u003e\u003ca href=\\\"https://gab.com/RealAlexJones\\\" class=\\\"u-url mention\\\"\u003e@\u003cspan\u003eRealAlexJones\u003c/span\u003e\u003c/a\u003e\u003c/span\u003e \u003c/p\u003e\u003cp\u003eThe stats prove that niggers make America worse, not better.\u003c/p\u003e\",\"reblog\":null,\"quote\":null,\"application\":{\"name\":\"Web\",\"website\":null},\"account\":{\"id\":\"62860\",\"username\":\"PatDollard\",\"acct\":\"PatDollard\",\"display_name\":\"Patrick Dollard\",\"locked\":false,\"bot\":false,\"created_at\":\"2016-11-20T00:42:26.000Z\",\"note\":\"\u003cp\u003eHollywood talent agent turned war correspondent turned jihadi killer turned Breitbart journalist/operative turned independent destroyer of worlds, creator of better ones. And proprietor of PatDollard.com and some other shady shit.\u003c/p\u003e\",\"url\":\"https://gab.com/PatDollard\",\"avatar\":\"https://gab.com/media/user/5831d0ab0558a.jpeg\",\"avatar_static\":\"https://gab.com/media/user/5831d0ab0558a.jpeg\",\"header\":\"https://gab.com/media/user/58334fcc4bfe0.jpg\",\"header_static\":\"https://gab.com/media/user/58334fcc4bfe0.jpg\",\"followers_count\":5698,\"following_count\":392,\"statuses_count\":19951,\"is_pro\":false,\"is_verified\":true,\"is_donor\":false,\"is_investor\":false,\"emojis\":[],\"fields\":[]},\"group\":null,\"media_attachments\":[],\"mentions\":[{\"id\":\"76559\",\"username\":\"RealAlexJones\",\"url\":\"https://gab.com/RealAlexJones\",\"acct\":\"RealAlexJones\"}],\"tags\":[],\"emojis\":[],\"card\":null,\"poll\":null},{\"id\":\"103525032468674220\",\"created_at\":\"2020-01-22T04:17:13.213Z\",\"revised_at\":null,\"in_reply_to_id\":\"103524990046017137\",\"in_reply_to_account_id\":\"62860\",\"sensitive\":false,\"spoiler_text\":\"\",\"visibility\":\"public\",\"language\":\"en\",\"uri\":\"https://gab.com/users/Lorenzot1990/statuses/103525032468674220\",\"url\":\"https://gab.com/Lorenzot1990/posts/103525032468674220\",\"replies_count\":1,\"reblogs_count\":0,\"favourites_count\":0,\"quote_of_id\":null,\"favourited\":false,\"reblogged\":false,\"muted\":false,\"content\":\"\u003cp\u003e\u003cspan class=\\\"h-card\\\"\u003e\u003ca href=\\\"https://gab.com/PatDollard\\\" class=\\\"u-url mention\\\"\u003e@\u003cspan\u003ePatDollard\u003c/span\u003e\u003c/a\u003e\u003c/span\u003e \u003cspan class=\\\"h-card\\\"\u003e\u003ca href=\\\"https://gab.com/RealAlexJones\\\" class=\\\"u-url mention\\\"\u003e@\u003cspan\u003eRealAlexJones\u003c/span\u003e\u003c/a\u003e\u003c/span\u003e pat... I think you\u0026apos;re supposed to be over there on Twitter with the rest of the Demonrats you racist twat.\u003c/p\u003e\",\"reblog\":null,\"quote\":null,\"application\":{\"name\":\"Web\",\"website\":null},\"account\":{\"id\":\"1007544\",\"username\":\"Lorenzot1990\",\"acct\":\"Lorenzot1990\",\"display_name\":\"Lorenzo Torres\",\"locked\":false,\"bot\":false,\"created_at\":\"2019-03-02T23:39:40.000Z\",\"note\":\"\u003cp\u003eRepublics are created by the virtue, public spirit, and intelligence of the citizens. They fall, when the wise are banished from the public councils, because they dare to be honest, and the profligate are rewarded, because they flatter the people, in order to betray them.\u003c/p\u003e\u003cp\u003eJoseph Story, 1833\u003c/p\u003e\",\"url\":\"https://gab.com/Lorenzot1990\",\"avatar\":\"https://gab.com/media/user/bq-5caffca0b255d.jpeg\",\"avatar_static\":\"https://gab.com/media/user/bq-5caffca0b255d.jpeg\",\"header\":\"https://gab.com/media/user/bq-5caffc096f224.jpeg\",\"header_static\":\"https://gab.com/media/user/bq-5caffc096f224.jpeg\",\"followers_count\":111,\"following_count\":62,\"statuses_count\":2414,\"is_pro\":false,\"is_verified\":false,\"is_donor\":false,\"is_investor\":false,\"emojis\":[],\"fields\":[]},\"group\":null,\"media_attachments\":[],\"mentions\":[{\"id\":\"62860\",\"username\":\"PatDollard\",\"url\":\"https://gab.com/PatDollard\",\"acct\":\"PatDollard\"},{\"id\":\"76559\",\"username\":\"RealAlexJones\",\"url\":\"https://gab.com/RealAlexJones\",\"acct\":\"RealAlexJones\"}],\"tags\":[],\"emojis\":[],\"card\":null,\"poll\":null},{\"id\":\"103525039008557070\",\"created_at\":\"2020-01-22T04:18:53.043Z\",\"revised_at\":null,\"in_reply_to_id\":\"103525032468674220\",\"in_reply_to_account_id\":\"1007544\",\"sensitive\":false,\"spoiler_text\":\"\",\"visibility\":\"public\",\"language\":\"en\",\"uri\":\"https://gab.com/users/PatDollard/statuses/103525039008557070\",\"url\":\"https://gab.com/PatDollard/posts/103525039008557070\",\"replies_count\":1,\"reblogs_count\":0,\"favourites_count\":0,\"quote_of_id\":null,\"favourited\":false,\"reblogged\":false,\"muted\":false,\"content\":\"\u003cp\u003e\u003cspan class=\\\"h-card\\\"\u003e\u003ca href=\\\"https://gab.com/Lorenzot1990\\\" class=\\\"u-url mention\\\"\u003e@\u003cspan\u003eLorenzot1990\u003c/span\u003e\u003c/a\u003e\u003c/span\u003e \u003cspan class=\\\"h-card\\\"\u003e\u003ca href=\\\"https://gab.com/RealAlexJones\\\" class=\\\"u-url mention\\\"\u003e@\u003cspan\u003eRealAlexJones\u003c/span\u003e\u003c/a\u003e\u003c/span\u003e \u003c/p\u003e\u003cp\u003eRacism is a God-given instinct.  You have to be brainwashed into insanity to no longer be racist, and people who aren\u0026apos;t racist are responsible for all the Muslim rapes in Europe and the downfall of the West entirely.  You\u0026apos;re not a man, you\u0026apos;re a coward and a eunuch too weak to protect his own people.\u003c/p\u003e\",\"reblog\":null,\"quote\":null,\"application\":{\"name\":\"Web\",\"website\":null},\"account\":{\"id\":\"62860\",\"username\":\"PatDollard\",\"acct\":\"PatDollard\",\"display_name\":\"Patrick Dollard\",\"locked\":false,\"bot\":false,\"created_at\":\"2016-11-20T00:42:26.000Z\",\"note\":\"\u003cp\u003eHollywood talent agent turned war correspondent turned jihadi killer turned Breitbart journalist/operative turned independent destroyer of worlds, creator of better ones. And proprietor of PatDollard.com and some other shady shit.\u003c/p\u003e\",\"url\":\"https://gab.com/PatDollard\",\"avatar\":\"https://gab.com/media/user/5831d0ab0558a.jpeg\",\"avatar_static\":\"https://gab.com/media/user/5831d0ab0558a.jpeg\",\"header\":\"https://gab.com/media/user/58334fcc4bfe0.jpg\",\"header_static\":\"https://gab.com/media/user/58334fcc4bfe0.jpg\",\"followers_count\":5698,\"following_count\":392,\"statuses_count\":19951,\"is_pro\":false,\"is_verified\":true,\"is_donor\":false,\"is_investor\":false,\"emojis\":[],\"fields\":[]},\"group\":null,\"media_attachments\":[],\"mentions\":[{\"id\":\"1007544\",\"username\":\"Lorenzot1990\",\"url\":\"https://gab.com/Lorenzot1990\",\"acct\":\"Lorenzot1990\"},{\"id\":\"76559\",\"username\":\"RealAlexJones\",\"url\":\"https://gab.com/RealAlexJones\",\"acct\":\"RealAlexJones\"}],\"tags\":[],\"emojis\":[],\"card\":null,\"poll\":null},{\"id\":\"103525054349618796\",\"created_at\":\"2020-01-22T04:22:47.090Z\",\"revised_at\":null,\"in_reply_to_id\":\"103525039008557070\",\"in_reply_to_account_id\":\"62860\",\"sensitive\":false,\"spoiler_text\":\"\",\"visibility\":\"public\",\"language\":\"en\",\"uri\":\"https://gab.com/users/Lorenzot1990/statuses/103525054349618796\",\"url\":\"https://gab.com/Lorenzot1990/posts/103525054349618796\",\"replies_count\":1,\"reblogs_count\":0,\"favourites_count\":0,\"quote_of_id\":null,\"favourited\":false,\"reblogged\":false,\"muted\":false,\"content\":\"\u003cp\u003e\u003cspan class=\\\"h-card\\\"\u003e\u003ca href=\\\"https://gab.com/PatDollard\\\" class=\\\"u-url mention\\\"\u003e@\u003cspan\u003ePatDollard\u003c/span\u003e\u003c/a\u003e\u003c/span\u003e \u003cspan class=\\\"h-card\\\"\u003e\u003ca href=\\\"https://gab.com/RealAlexJones\\\" class=\\\"u-url mention\\\"\u003e@\u003cspan\u003eRealAlexJones\u003c/span\u003e\u003c/a\u003e\u003c/span\u003e you\u0026apos;d rather have a white nation full of marxists and commies than admit that a black person can be just as much of a patriot? Sounds like you\u0026apos;re the coward. You\u0026apos;re name says it all... Dullard.\u003c/p\u003e\",\"reblog\":null,\"quote\":null,\"application\":{\"name\":\"Web\",\"website\":null},\"account\":{\"id\":\"1007544\",\"username\":\"Lorenzot1990\",\"acct\":\"Lorenzot1990\",\"display_name\":\"Lorenzo Torres\",\"locked\":false,\"bot\":false,\"created_at\":\"2019-03-02T23:39:40.000Z\",\"note\":\"\u003cp\u003eRepublics are created by the virtue, public spirit, and intelligence of the citizens. They fall, when the wise are banished from the public councils, because they dare to be honest, and the profligate are rewarded, because they flatter the people, in order to betray them.\u003c/p\u003e\u003cp\u003eJoseph Story, 1833\u003c/p\u003e\",\"url\":\"https://gab.com/Lorenzot1990\",\"avatar\":\"https://gab.com/media/user/bq-5caffca0b255d.jpeg\",\"avatar_static\":\"https://gab.com/media/user/bq-5caffca0b255d.jpeg\",\"header\":\"https://gab.com/media/user/bq-5caffc096f224.jpeg\",\"header_static\":\"https://gab.com/media/user/bq-5caffc096f224.jpeg\",\"followers_count\":111,\"following_count\":62,\"statuses_count\":2414,\"is_pro\":false,\"is_verified\":false,\"is_donor\":false,\"is_investor\":false,\"emojis\":[],\"fields\":[]},\"group\":null,\"media_attachments\":[],\"mentions\":[{\"id\":\"62860\",\"username\":\"PatDollard\",\"url\":\"https://gab.com/PatDollard\",\"acct\":\"PatDollard\"},{\"id\":\"76559\",\"username\":\"RealAlexJones\",\"url\":\"https://gab.com/RealAlexJones\",\"acct\":\"RealAlexJones\"}],\"tags\":[],\"emojis\":[],\"card\":null,\"poll\":null},{\"id\":\"103525069492414759\",\"created_at\":\"2020-01-22T04:26:38.175Z\",\"revised_at\":null,\"in_reply_to_id\":\"103525054349618796\",\"in_reply_to_account_id\":\"1007544\",\"sensitive\":false,\"spoiler_text\":\"\",\"visibility\":\"public\",\"language\":\"en\",\"uri\":\"https://gab.com/users/PatDollard/statuses/103525069492414759\",\"url\":\"https://gab.com/PatDollard/posts/103525069492414759\",\"replies_count\":0,\"reblogs_count\":0,\"favourites_count\":1,\"quote_of_id\":null,\"favourited\":false,\"reblogged\":false,\"muted\":false,\"content\":\"\u003cp\u003e\u003cspan class=\\\"h-card\\\"\u003e\u003ca href=\\\"https://gab.com/Lorenzot1990\\\" class=\\\"u-url mention\\\"\u003e@\u003cspan\u003eLorenzot1990\u003c/span\u003e\u003c/a\u003e\u003c/span\u003e \u003cspan class=\\\"h-card\\\"\u003e\u003ca href=\\\"https://gab.com/RealAlexJones\\\" class=\\\"u-url mention\\\"\u003e@\u003cspan\u003eRealAlexJones\u003c/span\u003e\u003c/a\u003e\u003c/span\u003e \u003c/p\u003e\u003cp\u003eMultiracialism is a weapon of Jewish communism and white genocide.\u003c/p\u003e\u003cp\u003eYou\u0026apos;re not only an uneducated and ignorant buffoon, but nature condemns you as a coward and a eunuch too weak to protect your own women and children.\u003c/p\u003e\",\"reblog\":null,\"quote\":null,\"application\":{\"name\":\"Web\",\"website\":null},\"account\":{\"id\":\"62860\",\"username\":\"PatDollard\",\"acct\":\"PatDollard\",\"display_name\":\"Patrick Dollard\",\"locked\":false,\"bot\":false,\"created_at\":\"2016-11-20T00:42:26.000Z\",\"note\":\"\u003cp\u003eHollywood talent agent turned war correspondent turned jihadi killer turned Breitbart journalist/operative turned independent destroyer of worlds, creator of better ones. And proprietor of PatDollard.com and some other shady shit.\u003c/p\u003e\",\"url\":\"https://gab.com/PatDollard\",\"avatar\":\"https://gab.com/media/user/5831d0ab0558a.jpeg\",\"avatar_static\":\"https://gab.com/media/user/5831d0ab0558a.jpeg\",\"header\":\"https://gab.com/media/user/58334fcc4bfe0.jpg\",\"header_static\":\"https://gab.com/media/user/58334fcc4bfe0.jpg\",\"followers_count\":5698,\"following_count\":392,\"statuses_count\":19951,\"is_pro\":false,\"is_verified\":true,\"is_donor\":false,\"is_investor\":false,\"emojis\":[],\"fields\":[]},\"group\":null,\"media_attachments\":[],\"mentions\":[{\"id\":\"1007544\",\"username\":\"Lorenzot1990\",\"url\":\"https://gab.com/Lorenzot1990\",\"acct\":\"Lorenzot1990\"},{\"id\":\"76559\",\"username\":\"RealAlexJones\",\"url\":\"https://gab.com/RealAlexJones\",\"acct\":\"RealAlexJones\"}],\"tags\":[],\"emojis\":[],\"card\":null,\"poll\":null},{\"id\":\"103525028049382176\",\"created_at\":\"2020-01-22T04:16:05.725Z\",\"revised_at\":null,\"in_reply_to_id\":\"103524882982132197\",\"in_reply_to_account_id\":\"76559\",\"sensitive\":false,\"spoiler_text\":\"\",\"visibility\":\"public\",\"language\":\"en\",\"uri\":\"https://gab.com/users/Lorenzot1990/statuses/103525028049382176\",\"url\":\"https://gab.com/Lorenzot1990/posts/103525028049382176\",\"replies_count\":0,\"reblogs_count\":0,\"favourites_count\":0,\"quote_of_id\":null,\"favourited\":false,\"reblogged\":false,\"muted\":false,\"content\":\"\u003cp\u003e\u003cspan class=\\\"h-card\\\"\u003e\u003ca href=\\\"https://gab.com/RealAlexJones\\\" class=\\\"u-url mention\\\"\u003e@\u003cspan\u003eRealAlexJones\u003c/span\u003e\u003c/a\u003e\u003c/span\u003e lol, love that old guy at the end of the video. \u0026quot;Fuck you!\u0026quot;\u003c/p\u003e\",\"reblog\":null,\"quote\":null,\"application\":{\"name\":\"Web\",\"website\":null},\"account\":{\"id\":\"1007544\",\"username\":\"Lorenzot1990\",\"acct\":\"Lorenzot1990\",\"display_name\":\"Lorenzo Torres\",\"locked\":false,\"bot\":false,\"created_at\":\"2019-03-02T23:39:40.000Z\",\"note\":\"\u003cp\u003eRepublics are created by the virtue, public spirit, and intelligence of the citizens. They fall, when the wise are banished from the public councils, because they dare to be honest, and the profligate are rewarded, because they flatter the people, in order to betray them.\u003c/p\u003e\u003cp\u003eJoseph Story, 1833\u003c/p\u003e\",\"url\":\"https://gab.com/Lorenzot1990\",\"avatar\":\"https://gab.com/media/user/bq-5caffca0b255d.jpeg\",\"avatar_static\":\"https://gab.com/media/user/bq-5caffca0b255d.jpeg\",\"header\":\"https://gab.com/media/user/bq-5caffc096f224.jpeg\",\"header_static\":\"https://gab.com/media/user/bq-5caffc096f224.jpeg\",\"followers_count\":111,\"following_count\":62,\"statuses_count\":2414,\"is_pro\":false,\"is_verified\":false,\"is_donor\":false,\"is_investor\":false,\"emojis\":[],\"fields\":[]},\"group\":null,\"media_attachments\":[],\"mentions\":[{\"id\":\"76559\",\"username\":\"RealAlexJones\",\"url\":\"https://gab.com/RealAlexJones\",\"acct\":\"RealAlexJones\"}],\"tags\":[],\"emojis\":[],\"card\":null,\"poll\":null}]}");
//echo template("gab.com","/RealAlexJones",templatehelp("https://gab.com/api/v1/accounts/76559/statuses"),templatehelp("https://gab.com/api/v1/accounts/76559/statuses?pinned=true"),templatehelp("https://gab.com/api/v1/account_by_username/RealAlexJones"));
//imgur
//echo template("imgur.com","https://imgur.com/t/itsover9000",templatehelp("https://imgur.com/t/itsover9000"),"","");
//echo template("imgur.com","https://imgur.com/t/itsover9000/3O8Up",templatehelp("https://imgur.com/t/itsover9000/3O8Up"),"","");

//echo template("imgur.com","",templatehelp("https://imgur.com/search?q=inuyasha"),"","");
//echo template("imgur.com","https://imgur.com/ER1XFdA",templatehelp("https://imgur.com/ER1XFdA"),"","");
//reddit
//echo template("reddit.com","",templatehelp("https://www.reddit.com/r/PedoGate/comments/e3f8zf/i_was_sex_trafficked_to_joe_biden_prince_andrew/"),"","");
//instagram post
//echo template("instagram.com","/p/",templatehelp("https://www.instagram.com/p/B6uFDzqBUjB/"),"","");
//echo template("instagram.com","",templatehelp("https://www.instagram.com/annieshomegrown/?hl=en"),"","");
//echo template("instagram.com","",templatehelp("https://www.instagram.com/justinsun/?hl=en"),"","");

//template cnn (server  is geoblocked from using us version.
//us version is in a format of "https://www.cnn.com/data/ocs/section/index.html:homepage1-zone-1/views/zones/common/zone-manager.izl
//echo template("cnn.com","",templatehelp("https://cnn.com"),templatehelp("https://www.cnn.com/data/ocs/section/_homepage-zone-injection/index.html:homepage-injection-zone-1/views/zones/common/zone-manager.izl"),templatehelp("https://edition.cnn.com/data/ocs/section/index.html:intl_homepage1-zone-1/views/zones/common/zone-manager.izl").templatehelp("https://edition.cnn.com/data/ocs/section/index.html:intl_homepage1-zone-2/views/zones/common/zone-manager.izl").templatehelp("https://edition.cnn.com/data/ocs/section/index.html:intl_homepage1-zone-3/views/zones/common/zone-manager.izl").templatehelp("https://edition.cnn.com/data/ocs/section/index.html:intl_homepage1-zone-4/views/zones/common/zone-manager.izl").templatehelp("https://edition.cnn.com/data/ocs/section/index.html:intl_homepage2-zone-1/views/zones/common/zone-manager.izl").templatehelp("https://edition.cnn.com/data/ocs/section/index.html:intl_homepage3-zone-1/views/zones/common/zone-manager.izl").templatehelp("https://edition.cnn.com/data/ocs/section/index.html:intl_homepage3-zone-3/views/zones/common/zone-manager.izl").templatehelp("https://edition.cnn.com/data/ocs/section/index.html:intl_homepage3-zone-4/views/zones/common/zone-manager.izl"));
//youtube test
//echo template("youtube.com","",templatehelp("https://www.youtube.com/watch?v=3kAGbOScuz0"),"","");
//facebook
//echo template("facebook.com","",templatehelp("https://www.facebook.com/epochtimes/"),"","");
//echo template("facebook.com","",templatehelp("https://www.facebook.com/epochtimes/posts/10158153934629266"),"","");
//pinterest
//echo template("pinterest.com","",templatehelp("https://www.pinterest.com/tkentguns/firearm-schematics-other-charts/"),"","");
//echo template("pinterest.com","/pin/",templatehelp("https://www.pinterest.com/pin/368591550728522666"),"","");
//echo template("pinterest.com","/user/",templatehelp("https://www.pinterest.com/giordanim/"),"https://www.pinterest.com/giordanim/","");

function templatehelp($a) {
//echo "<br><br>a:".$a;
$ch2 = curl_init();
curl_setopt($ch2,CURLOPT_URL, $a);
curl_setopt($ch2,CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch2,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch2, CURLOPT_ENCODE, "");
//curl_setopt($ch2,CURLOPT_HEADER, 1);
curl_setopt($ch2,CURLOPT_HTTPHEADER, array('accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3','accept-language:en-US,en;q=0.9','cache-control: no-cache','DNT:1','pragma:no-cache','sec-fetch-mode: navigate','sec-fetch-site: none','sec-fetch-user: ?1','upgrade-insecure-requests: 1','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36','Connection:Close'));
$result="";
if($result=curl_exec($ch2)) {}
//echo "<br><br>curl error next";
//var_dump(curl_error($ch2));
//echo strlen($result);
return $result;}

function cnn($a) {
if (strpos($a,"<img class=")!==FALSE) {
$ex=explode("<img class=",$a);
$d=count($ex);
$r=$ex[0];
for($i=$i;$i<$d;$i++) {
$post=substr($ex[$i],strpos($ex[$i],">"));
$alt=substr($ex[$i],strpos($ex[$i],"<img alt=")+4);
$alt=substr($alt,0,strpos($alt,">"));
$r.="<img ".$alt.$post;}
} else {$r=$a;}
return $r;
}
?>
