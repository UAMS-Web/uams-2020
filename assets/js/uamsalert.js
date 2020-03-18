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
    console.log( objAlertData.posts.length );

    // Alert colors
    arrAlertTypes = {};
        arrAlertTypes[alertUrgent] = "uamsalert-urgent";
        arrAlertTypes[alertAlert] = "uamsalert-alert";
        arrAlertTypes[alertFyi] = "uamsalert-fyi";

    // };
    for (i = 0; i < objAlertData.posts.length; i++) {
        var arrCategories = objAlertData.posts[i].categories;
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
                var strAlertTitle    = objAlertData.posts[i].title;
                var strAlertLink     = objAlertData.posts[i].URL;
                // var strAlertMessage  = objAlertData.posts[0].excerpt; // Not used
                var strAlertContent  = objAlertData.posts[i].content;
                var strAlertColor    = arrAlertTypes[objCategory.slug] ? arrAlertTypes[objCategory.slug] : objFakeCat.slug;
                strSuccess           = true;
            }
        }

        // Banners must have an actual color
        // Don't load anything unless we have something to present
        if (strSuccess)
        {
            addElement(strAlertTitle,strAlertLink,strAlertColor,strAlertContent); // Removed strAlertMessage
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
            break;
        }
    }
}

// addElement - display HTML on page right below the body page
// don't want the alert to show up randomly
function addElement(strAlertTitle,strAlertLink,strAlertColor,strAlertContent) // Removed strAlertMessage
{
    // Grab the tag to start the party
    var bodyTag = document.getElementsByTagName('body')[0];

    bodyTag.style.margin = '0px';
    bodyTag.style.padding = '0px';
    bodyTag.className += ' uams-alert';

    var wrapperSection = document.createElement('section');
    wrapperSection.setAttribute('id','uamsalert-alert-message');
    wrapperSection.setAttribute('class', 'uams-module less-padding cta-bar cta-bar-sm ' + strAlertColor + ' ' + strSiteStatus);

    var wrapperContainer = document.createElement('div');
    wrapperContainer.setAttribute('class', 'container-fluid');

    var wrapperRow = document.createElement('div');
    wrapperRow.setAttribute('class', 'row');

    var wrapperCol = document.createElement('div');
    wrapperCol.setAttribute('class', 'col-12');

    var wrapperInner = document.createElement('div');
    wrapperInner.setAttribute('class', 'inner-container');

    var wrapperHeading = document.createElement('div');
    wrapperHeading.setAttribute('class', 'cta-heading');

    var wrapperBody = document.createElement('div');
    wrapperBody.setAttribute('class', 'cta-body');

    var wrapperText = document.createElement('div');
    wrapperText.setAttribute('class', 'text-container');
    wrapperText.innerHTML = strAlertContent;
    // Split HTML if read more is used
    if(wrapperText.innerHTML.indexOf("<!--noteaser-->") !== -1) {
        wrapperText.innerHTML = wrapperText.innerHTML.split('<!--noteaser-->', 1);
        var alertLinkTrue = true; // Set to true if more link is used
    }

    var wrapperBtn = document.createElement('div');
    wrapperBtn.setAttribute('class', 'btn-container');

    var alertBoxTextDiv = document.createElement('div');
    alertBoxTextDiv.setAttribute('id','uamsalert-alert-inner');
    alertBoxTextDiv.setAttribute('class', strAlertColor + ' ' + strSiteStatus);

    var anchorLink = document.createElement('a');
    anchorLink.setAttribute('href', strAlertLink);
    anchorLink.setAttribute('title', strAlertTitle);

    var alertHeading = document.createElement('h1');

    var contentDiv = document.createElement('div');
    contentDiv.setAttribute('id', 'uamsalert-alert-content');

    var alertLinkDiv = document.createElement('div');
    alertLinkDiv.setAttribute('id', 'uamsalert-alert-more');

    // -- Remove Link -- //
    // Supporting titles with special characters
    // try
    // {
    //     anchorLink.innerHTML = strAlertTitle;
    // }
    // catch (err)
    // {
    //     var headerDivText = document.createTextNode(strAlertTitle);
    //     anchorLink.appendChild(headerDivText);

    // }
    // alertHeading.appendChild(anchorLink);

    // -- Remove Excerpt -- //
    // var div = document.createElement("div");
    // div.innerHTML = strAlertMessage;
    // // Strip out html that wordpress.com gives us
    // var alertTextMessage = div.textContent || div.innerText || "";
    // // Build alert text node and cut of max characters
    // var alertText = document.createTextNode(
    // alertTextMessage.substring(0,360) +
    //     (alertTextMessage.length >= 360 ? '... ' : ' ')
    // );
    // alertTextP.appendChild(alertText);

    // Header Text - No link
    alertHeading.innerHTML = strAlertTitle;

    // Build the alert link
    var alertLink = document.createElement('a');
    alertLink.setAttribute('class', 'btn btn-white');
    alertLink.setAttribute('href', strAlertLink);
    alertLink.setAttribute('title', strAlertTitle);
    var alertLinkText = document.createTextNode('Read More');
    alertLink.appendChild(alertLinkText);

    // Standard/Base Link - Inclement Weather
    var alertLinkBase = document.createElement('a');
    alertLinkBase.setAttribute('class', 'btn btn-outline-white');
    alertLinkBase.setAttribute('href', 'https://uamshealth.com/weather/');
    alertLinkBase.setAttribute('title', 'UAMS Inclement Weather');
    var alertLinkTextBase = document.createTextNode('All Updates');
    alertLinkBase.appendChild(alertLinkTextBase);
    alertLinkBaseInclude = false; // Set to true if we want to include base link

    var alertLinkContainer = false;
    if(alertLinkTrue) {
        wrapperBtn.appendChild(alertLink); // Add more link to container
        alertLinkContainer = true; // Set to true so link container is added to body container
    }

    if(alertLinkBaseInclude) {
        wrapperBtn.appendChild(alertLinkBase); // Add standard / base link to container
        alertLinkContainer = true; // Set to true so link container is added to body container
    }

    // Start Building the Actual Div

    wrapperHeading.appendChild(alertHeading);

    wrapperBody.appendChild(wrapperText);

    // Add link container if needed
    if(alertLinkContainer) {
        wrapperBody.appendChild(wrapperBtn);
        wrapperSection.setAttribute('class', wrapperSection.getAttribute('class') + ' cta-bar-weighted');
    } else {
        wrapperSection.setAttribute('class', wrapperSection.getAttribute('class') + ' cta-bar-centered no-link');
    }

    wrapperInner.appendChild(wrapperHeading);
    wrapperInner.appendChild(wrapperBody);

    wrapperCol.appendChild(wrapperInner);

    wrapperRow.appendChild(wrapperCol);

    wrapperContainer.appendChild(wrapperRow);

    wrapperSection.appendChild(wrapperContainer);
    wrapperSection.appendChild(alertBoxTextDiv);

    bodyTag.insertBefore(wrapperSection, bodyTag.firstChild);
}
