/*  University of Arkansas for Medical Sciences - Alert 2.0 Beta
 *  Based on UW Alert Banner - https://github.com/uweb/UW-Alert-Banner
 *  (c) 2011 Chris Heiland
 *
 *  Script should be included like such:
 *
 *  <html>
 *  <head>
 *  <title>Page Title</title>
 *  </head>
 *  <body>
 *
 *  <script type="text/javascript" src="//www.uams.edu/web/alert/alert.js"></script>
 *  </body>
 *  </html>
 *
 *  Original docs at:
 *  uw.edu/marketing/uw-alert-banner/
 *
 *--------------------------------------------------------------------------*/

// Thanks Dane!
var strTestStatus = window.location.hash.indexOf('uamsalert') === -1 ? 'false' : 'true';
// Allow for local testing
var strDomain = (window.location.hostname == 'localhost') ? 'localhost' : 'www.uams.edu/web/alert';
var strDataFeed = '/data/?c=displayAlert&test='+strTestStatus;
var strBaseUrl = window.location.protocol + '//' + strDomain;
// var strBaseUrl = 'http://' + strDomain; // Test
var strSiteStatus = '';

if (document.body.className.indexOf('uamshealth')!== -1) {
    strSiteStatus = 'uamshealth';
} else if (document.body.className.indexOf('insidenew')!== -1) {
    strSiteStatus = 'insidenew';
} else if (document.body.className.indexOf('inside')!== -1) {
    strSiteStatus = 'inside';
} else {
    strSiteStatus = 'uams';
}

var strScript = document.createElement('script');
strScript.setAttribute('src', strBaseUrl + strDataFeed);
document.getElementsByTagName('head')[0].appendChild(strScript);

// displayAlert - grab content to display message
function displayAlert(objAlertData)
{

    // Just in case w.com delivers us something bad
    // or We don't care if the feed is null
    if ((!objAlertData) || (objAlertData.found == 0))
        return false;

    var alertUrgent = "urgent-"+ strSiteStatus;
    var alertAlert = "alert-"+ strSiteStatus;
    var alertFyi = "fyi-"+ strSiteStatus;
    var alertTest = strSiteStatus;

    console.log( alertTest );

    // Alert colors
    arrAlertTypes = {};
        arrAlertTypes[alertUrgent] = "uamsalert-urgent";
        arrAlertTypes[alertAlert] = "uamsalert-alert";
        arrAlertTypes[alertFyi] = "uamsalert-fyi";

    // };

    var arrCategories = objAlertData.posts[0].categories;
    // If there is a test alert
    if ( window.location.hash.indexOf('uamsalert') != -1 )
    {
        // Sometimes we don't get a category from the w.com test feed
        var objFakeCat = new Object();
        var strTestAlertColor = window.location.hash.replace('#','');
        objFakeCat.slug = strTestAlertColor;
        arrCategories['Fake Category'] = objFakeCat;
    }

    var strSuccess = false;
    for (strCategory in arrCategories)
    {
        var objCategory = arrCategories[strCategory];
        // Quick way to determine color
        if (arrAlertTypes[objCategory.slug] || objFakeCat)
        {
            var strAlertTitle    = objAlertData.posts[0].title;
            var strAlertLink     = 'https://uamshealth.com/weather/';
            var strAlertMessage  = objAlertData.posts[0].excerpt;
            var strAlertColor    = arrAlertTypes[objCategory.slug] ? arrAlertTypes[objCategory.slug] : objFakeCat.slug;
            strSuccess           = true;
        }
    }

    // Banners must have an actual color
    // Don't load anything unless we have something to present
    if (strSuccess)
    {
        addElement(strAlertTitle,strAlertLink,strAlertColor,strAlertMessage);
        // Code contributed by Dustin Brewer
        var strCSS = document.createElement('link');
        //strCSS.setAttribute('href', strBaseUrl + '/uamsalert.css');
        // strCSS.setAttribute('href', './uamsalert.css');
        strCSS.setAttribute('rel','stylesheet');
        strCSS.setAttribute('type','text/css');
        document.getElementsByTagName('head')[0].appendChild(strCSS);
        // Because content is loaded dynamically, need to wait to grab the height
        setTimeout(function() {
            var strHeight = document.getElementById('uamsalert-alert-message').offsetHeight;
            var bodyTag = document.getElementsByTagName('body')[0];
            bodyTag.style.backgroundPosition='0px '+strHeight+'px';
        },10);

    }

}

// addElement - display HTML on page right below the body page
// don't want the alert to show up randomly
function addElement(strAlertTitle,strAlertLink,strAlertColor,strAlertMessage)
{
    // Grab the tag to start the party
    var bodyTag = document.getElementsByTagName('body')[0];

    bodyTag.style.margin = '0px';
    bodyTag.style.padding = '0px';
    bodyTag.className += ' uams-alert';

    var wrapperSection = document.createElement('section');
    wrapperSection.setAttribute('id','uamsalert-alert-message');
    wrapperSection.setAttribute('class', 'uams-module ' + strAlertColor + ' ' + strSiteStatus);

    var wrapperContainer = document.createElement('div');
    wrapperContainer.setAttribute('class', 'container-fluid');

    var wrapperRow = document.createElement('div');
    wrapperRow.setAttribute('class', 'row');

    var wrapperCol = document.createElement('div');
    wrapperCol.setAttribute('class', 'col-xs-12');

    var wrapperBody = document.createElement('div');
    wrapperBody.setAttribute('class', 'module-body');

    var alertHeadingLink = document.createElement('a');
    alertHeadingLink.setAttribute('href', strAlertLink);
    alertHeadingLink.setAttribute('title', strAlertTitle);

    var alertHeading = document.createElement('h2');

    // Supporting titles with special characters
    try
    {
        alertHeadingLink.innerHTML = strAlertTitle;
    }
    catch (err)
    {
        var header1Text = document.createTextNode(strAlertTitle);
        alertHeadingLink.appendChild(header1Text);

    }
    alertHeading.appendChild(alertHeadingLink);

    var alertTextP = document.createElement('p');

    var div = document.createElement("div");
    div.innerHTML = strAlertMessage;
    // Strip out html that wordpress.com gives us
    var alertTextMessage = div.textContent || div.innerText || "";
    // Build alert text node and cut of max characters
    var alertText = document.createTextNode(
    alertTextMessage.substring(0,360) +
        (alertTextMessage.length >= 360 ? '... ' : ' ')
    );
    alertTextP.appendChild(alertText);

    var alertLink = document.createElement('a');
    alertLink.setAttribute('class', 'btn btn-white');
    alertLink.setAttribute('href', strAlertLink);
    alertLink.setAttribute('title', strAlertTitle);
    var alertLinkText = document.createTextNode('More Info');
    alertLink.appendChild(alertLinkText);

    // Start Building the Actual Div

    wrapperBody.appendChild(alertHeading);
    wrapperBody.appendChild(alertTextP);
    wrapperBody.appendChild(alertLink);

    wrapperCol.appendChild(wrapperBody);

    wrapperRow.appendChild(wrapperCol);

    wrapperContainer.appendChild(wrapperRow);

    wrapperSection.appendChild(wrapperContainer);

    bodyTag.insertBefore(wrapperSection, bodyTag.firstChild);
}
