<?php
/**
 * Custom Footer
 *
 * @package      Bootstrap for Genesis
 * @since        1.0
 * @link         http://webdevsuperfast.github.io
 * @author       Rotsen Mark Acob <webdevsuperfast.github.io>
 * @copyright    Copyright (c) 2015, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/

//* Customize the credits
add_filter( 'genesis_footer_creds_text', 'uamswp_footer_creds_text' );
function uamswp_footer_creds_text() {

    // Render this by default, in whatever structure is best.
    // If the site is an EDU site, replace the href value with "http://www.uams.edu".
    // If an SVG asset is uploaded/defined in the custom field, replace the href value with the home URL of the current site.
    // If an SVG asset is uploaded/defined in the custom field, replace the image path with the uploaded SVG asset.
    // The height of the image is controlled by CSS.
    /* Set the defaults */
    $site = uams_get_site_info()['site'];
    $subsite = uams_get_site_info()['subsite'];

    /**
     * Start Image
     */
    $footer_image_url = 'http://www.uams.edu';
    $footer_image_title = 'University of Arkansas for Medical Sciences';
    $footer_image_site = 'main-qualifier';

    /* Start the ifs */
    /* Institutes */
    if ('institute' == $site) {
        if (('' != $subsite) && ('uams' !== $subsite)) {
            $footer_image_site = $subsite;
            if ( 'institute_aging' == $subsite ) {
                $footer_image_url = 'https://aging.uams.edu/';
                $footer_image_title = 'Donald W. Reynolds Institute on Aging';
            } elseif ( 'institute_eye' == $subsite ) {
                $footer_image_url = 'https://eye.uams.edu/';
                $footer_image_title = 'Harvey & Bernice Jones Eye Institute';
            } elseif ( 'institute_spine' == $subsite ) {
                $footer_image_url = 'https://spine.uams.edu/';
                $footer_image_title = 'Jackson T. Stephens Spine & Neurosciences Institute';
            } elseif ( 'institute_digi-health' == $subsite ) {
                $footer_image_url = 'https://idhi.uams.edu/';
                $footer_image_title = 'Institute for Digital Health & Innovation';
            } elseif ( 'institute_pri'  == $subsite ) {
                $footer_image_url = 'https://psychiatry.uams.edu/';
                $footer_image_title = 'Psychiatric Research Institute';
            } elseif ( 'institute_tri' == $subsite ) {
                $footer_image_url = 'https://tri.uams.edu/';
                $footer_image_title = 'Translational Research Institute';
            } elseif ( 'institute_cancer' == $subsite ) {
                $footer_image_url = 'https://cancer.uams.edu/';
                $footer_image_title = 'Winthrop P. Rockefeller Cancer Institute';
            }
        }
    }
    /* UAMS Colleges, Regional Campuses & exceptions */
    if ('uams' == $site) {
        if ( startsWith($subsite, 'health-prof') ) {
            $footer_image_url = 'https://healthprofessions.uams.edu/';
            $footer_image_title = 'UAMS College of Health Professions';
            $footer_image_site = $subsite;
        } elseif ( startsWith($subsite, 'medicine') ) {
            $footer_image_url = 'https://medicine.uams.edu/';
            $footer_image_title = 'UAMS College of Medicine';
            $footer_image_site = $subsite;
        } elseif ( startsWith($subsite, 'nursing') ) {
            $footer_image_url = 'https://nursing.uams.edu/';
            $footer_image_title = 'UAMS College of Nursing';
            $footer_image_site = $subsite;
        } elseif ( startsWith($subsite, 'pharmacy') ) {
            $footer_image_url = 'https://pharmacy.uams.edu/';
            $footer_image_title = 'UAMS College of Pharmacy';
            $footer_image_site = $subsite;
        } elseif ( startsWith($subsite, 'public-health') ) {
            $footer_image_url = 'https://publichealth.uams.edu/';
            $footer_image_title = 'UAMS College of Publice Health';
            $footer_image_site = $subsite;
        } elseif ( startsWith($subsite, 'grad-school') ) {
            $footer_image_url = 'https://gradschool.uams.edu/';
            $footer_image_title = 'UAMS Graduate School';
            $footer_image_site = $subsite;
        // } elseif ( 'nw-campus' == $subsite ) {
        //     $footer_image_url = 'https://northwestcampus.uams.edu/';    
        //     $footer_image_title = 'UAMS Northwest Regional Campus';
        //     $footer_image_site = $subsite;
        // } elseif ( startsWith($subsite, 'regional-') ) {
        //     $footer_image_url = 'https://regionalprograms.uams.edu/'; 
        //     $footer_image_title = 'UAMS Regional Campuses';
        //     $footer_image_site = $subsite;
        // }    
        // elseif ( 'cda' == uams_get_site_info()['subsite'] ) { // Example
        //     $footer_image_url = 'https://cda.uams.edu/'; 
        //     $footer_image_title = 'Center for Diversity Affairs';
        //     $footer_image_site = 'UAMS-Logo_' . uams_get_site_info()['subsite'];
        }
    }
    /* UAMS Health */
    if ( 'uamshealth' == $site ){
        $footer_image_url = 'https://uamshealth.com/';
        $footer_image_site = 'main-tagline';
    } elseif ( 'inside' == $site ) {
        $footer_image_url = 'https://inside.uams.edu/';
        $footer_image_site = 'main';
    }
    $footer_image = '<img src="' . get_stylesheet_directory_uri() .'/assets/svg/uams-logo_'.$footer_image_site.'_horizontal_white.svg" alt="'. $footer_image_title .' Logo" />';
    
    printf( '<a href="%s" class="logo">%s<span class="sr-only">%s</span></a>', $footer_image_url, $footer_image, $footer_image_title );
    
    /**
     * End Image
     */

    /**
     * Start Address
     */
    /* Institutes, NW Campus, & Regional Campus get new address option */
    $custom_addresses = get_field( 'uamswp_address', 'option' ); 
    $custom_count = count($custom_addresses);  
    if ($custom_count < 2) {
        $address_sr = ' class="sr-only"';
    } 
    // Overrides, if available
    if( ! empty( $custom_addresses ) && ( ('institute' == $site) || ('nw-campus' == $subsite) || ( startsWith($subsite, 'regional-') ) ) ) {
        $address = '<div itemscope="" itemtype="http://schema.org/LocalBusiness" class="schema">';
        $address .= '<span itemprop="name" class="sr-only">'.$footer_image_title .'</span>';
        foreach ( $custom_addresses as $custom_address ) {
            $address .= '<div class="schema-address" itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">';
            $address .= '<strong'. (! empty($custom_address['address_title']) ? '>'. $custom_address['address_title'] : $address_sr .'>Mailing Address' ).':</strong> ';
            $address .= '<span itemprop="streetAddress">'. (! empty($custom_address['address_street_1']) ? $custom_address['address_street_1'] : '4301 West Markham Street' ) . (! empty($custom_address['address_street_2']) ? ' ' . $custom_address['address_street_2'] : '' ).'</span>, ';
            $address .= '<span itemprop="addressLocality">'. (! empty($custom_address['address_city']) ? $custom_address['address_city'] : 'Little Rock' ).'</span>, ';
            $address .= '<span itemprop="addressRegion">'. (! empty($custom_address['address_state']) ? $custom_address['address_state'] : 'AR' ).'</span> ';
            $address .= '<span itemprop="postalCode">'. (! empty($custom_address['address_zip']) ? $custom_address['address_zip'] : '72205' ).'</span>'; 
            $address .= '</div>';
        }
        echo $address;
    } else { //write default
        $address = '<div itemscope="" itemtype="http://schema.org/LocalBusiness" class="schema">';
        $address .= '<span itemprop="name" class="sr-only">University of Arkansas for Medical Sciences</span>';
        $address .= '<div class="schema-address" itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">';
        $address .= '<strong class="sr-only">Mailing Address:</strong> ';
        $address .= '<span itemprop="streetAddress">4301 West Markham Street</span>, ';
        $address .= '<span itemprop="addressLocality">Little Rock</span>, ';
        $address .= '<span itemprop="addressRegion">AR</span> ';
        $address .= '<span itemprop="postalCode">72205</span>';
        $address .= '</div>';
        echo $address;
    }


    /**
     * End Address
     */

     /**
     * Start Phone
     */
    $primary_phone_text = get_field( 'uamswp_primary_phone_text', 'option' ) ?: 'Phone';
    $primary_phone_number = get_field( 'uamswp_primary_phone_number', 'option' ) ?: '501-686-7000';
    $secondary_type = get_field( 'uamswp_secondary_number_type', 'option' );
    $secondary_link_text = get_field( 'uamswp_secondary_link_text', 'option' ) ?: 'Additional Phone Numbers';
    $secondary_link_url = get_field( 'uamswp_secondary_link_url', 'option' );
    $secondary_phone_text = get_field( 'uamswp_secondary_phone_text', 'option' ) ?: 'Additional Phone';
    $secondary_phone_number = get_field( 'uamswp_secondary_phone_number', 'option' );

    // Render this by default
    echo '<div class="schema-phone">';

    // Render this by default
    // Replace "span" with "strong" if any of the custom Parking Address fields DO have values.
    echo '<'. ($custom_count < 2 ? 'span' : 'strong') . ' id="footer-phone-label">';

    // Render this by default
    // Replace "Phone" with "Appoinments" if the relevant custom field ("Is Appointment Number" or something) is checked.
    echo $primary_phone_text;

    // Render this by default
    // Replace "span" with "strong" if any of the custom Parking Address fields DO have values.
    echo ':</'. ($custom_count < 2 ? 'span' : 'strong') .'> ';

    // Render this by default.
    // Replace both telephone values (text and href) if the custom telephone field has a value.
    // Format user input as "+1-XXX-XXX-XXXX" format in href="tel:" value
    // Format user input as "XXX-XXX-XXXX" format in text value.
    echo '<span itemprop="telephone"><a href="tel:'.format_phone('dash', $primary_phone_number).'" aria-labelledby="footer-phone-label">'. format_phone('us', $primary_phone_number) .'</a></span>';

    if ( 'link' == $secondary_type ) {
        // Render link.
        echo $secondary_link_url ? '<br /><a class="more-phone" href="'. $secondary_link_url['url'] .'">'. $secondary_link_text .'</a>' : '';
    } elseif ( 'phone' == $secondary_type ) { 
        // Render phone.
        echo $secondary_phone_number ? '</div><div class="schema-phone"><'. ($custom_count < 2 ? 'span' : 'strong') .' id="footer-phone-label-2">'. $secondary_phone_text .':</'.($custom_count < 2 ? 'span' : 'strong') .'> <span itemprop="telephone"><a href="tel:'.format_phone('dash', $secondary_phone_number).'" aria-labelledby="footer-phone-label-2">'. format_phone('us', $secondary_phone_number) .'</a></span>' : '';
    } else { // None
        // Do nothing
    }

    // Render this by default
    echo '</div>';
    echo '</div>';

    /**
     * End Phone
     */
    
    // Set Defaults 
    $social_fb = 'https://www.facebook.com/UAMShealth/';
    $social_tw = 'https://twitter.com/uamshealth';
    $social_ig = 'https://www.instagram.com/uamshealth/';
    $social_yt = 'https://www.youtube.com/user/UAMSHealth';
    $social_li = 'https://www.linkedin.com/school/university-of-arkansas-for-medical-sciences/'; // or 'https://www.linkedin.com/company/uams/'
    $social_pn = 'https://www.pinterest.com/uamshealth/';

    // Change the exceptions
    if ('institute' == $site) {
        if (('' != $subsite) && ('uams' !== $subsite)) {
            if ( 'institute_aging' == $subsite ) {
                $social_fb = 'https://www.facebook.com/UAMSaging/';
            } elseif ( 'institute_eye' == $subsite ) {
                $social_fb = 'https://www.facebook.com/pages/Harvey-Bernice-Jones-Eye-Institute/';
            } elseif ( 'institute_digi-health' == $subsite ) {
                $social_fb = 'https://www.facebook.com/UAMSDigitalHealth/';
            } elseif ( 'institute_pri'  == $subsite ) {
                $social_fb = 'http://www.facebook.com/PRI.UAMS';
            } elseif ( 'institute_tri' == $subsite ) {
                $social_fb = 'https://www.facebook.com/uamstri/';
                $social_tw = 'https://twitter.com/TRI_UAMS';
            } elseif ( 'institute_cancer' == $subsite ) {
                $social_fb = 'http://www.facebook.com/uamscancerinstitute';
                $social_tw = 'https://twitter.com/uamscancer';
                $social_ig = 'https://instagram.com/uamscancer';
                $social_li = 'https://www.linkedin.com/company/the-winthrop-p-cancer-institute/';
                $social_yt = 'http://www.youtube.com/watch?v=Y_w5HFmzgCo&list=PLDDEFFC8B6412D823';
            }
        }
    }
    /* UAMS Colleges, Regional Campuses & exceptions */
    if ('uams' == $site) {
        if ( startsWith($subsite, 'health-prof') ) {
            $social_fb = 'https://www.facebook.com/uamschp';
            $social_ig = 'https://www.instagram.com/uamschp';
        } elseif ( startsWith($subsite, 'medicine') ) {
            $social_fb = 'https://www.facebook.com/UAMSCOM/';
        } elseif ( startsWith($subsite, 'nursing') ) {
            $social_fb = 'https://www.facebook.com/pages/UAMS-College-of-Nursing/';
        } elseif ( startsWith($subsite, 'pharmacy') ) {
            $social_fb = 'https://www.facebook.com/UAMSPharm';
            $social_ig = 'https://www.instagram.com/uamspharmacy/';
        } elseif ( startsWith($subsite, 'public-health') ) {
            $social_fb = 'https://www.facebook.com/uamscoph/';
        } elseif ( startsWith($subsite, 'grad-school') ) {
            $social_fb = 'https://www.facebook.com/pages/UAMS-Graduate-School/';
        } elseif ( 'nw-campus' == $subsite ) {
            $social_fb = 'https://www.facebook.com/UAMSNW/';
        // } elseif ( startsWith($subsite, 'regional-') ) {
        //     $social_fb = '';
        } elseif ( 'pharmacy_arpoison' == $subsite ) {
             $social_fb = 'https://www.facebook.com/ArkDrugHelp/';
        } elseif ( 'get-healthy' == $subsite ) {
            $social_fb = 'https://www.facebook.com/GetHealthyUAMS/';
            $social_ig = 'https://www.instagram.com/GetHealthyUAMS/';
        } elseif ( 'cda' == $subsite ) {
           $social_fb = 'https://www.facebook.com/pages/UAMS-Center-for-Diversity-Affairs/';
        } elseif ( 'medicine_emergency-medicine' == $subsite) {
            $social_fb = 'https://www.facebook.com/UAMSEmergencyMedicine';
        } elseif ( 'medicine_pediatrics' == $subsite) {
            $social_fb = 'https://www.facebook.com/pages/UAMS-Department-of-Pediatrics/';
            $social_tw = 'https://twitter.com/UAMSPeds';
        } elseif ( 'medicine_otolaryngology' == $subsite) {
            $social_fb = 'https://www.facebook.com/pg/uamsotolaryngology/';
            $social_tw = 'https://twitter.com/UAMSENT';
        } elseif ( 'medicine_family-medicine' == $subsite) {
            $social_fb = 'https://www.facebook.com/UAMSFamilyMedicineResidency/';
        } elseif ( 'medicine_orthopaedic-surgery' == $subsite) {
            $social_fb = 'https://www.facebook.com/UAMSOrtho/';
        } elseif ( 'medicine_pathology' == $subsite) {
            $social_fb = 'https://www.facebook.com/pathologyuams/';
        } elseif ( 'medicine_urology' == $subsite) {
            $social_fb = 'https://www.facebook.com/UAMSUrology/';
        } elseif ( 'gsa' == $subsite) {
            $social_fb = 'https://www.facebook.com/UAMSgsa/';
        } elseif ( 'continuing-ed' == $subsite) {
            $social_fb = 'https://www.facebook.com/UAMSOCE/';
        } elseif ( 'health-literacy' == $subsite) {
            $social_fb = 'https://www.facebook.com/uamscenterforhealthliteracy';
            $social_tw = 'https://twitter.com/UAMS_CHL';
            $social_ig = 'https://www.instagram.com/uams_chl/';
        } elseif ( 'ipe' == $subsite) {
            $social_fb = 'https://www.facebook.com/uamsipe/';
        } elseif ( 'library' == $subsite) {
            $social_fb = 'http://www.facebook.com/uamslibrary';
            $social_tw = 'https://twitter.com/UAMSlibrary';
        } elseif ( 'employee_nurses' == $subsite) {
            $social_fb = 'https://www.facebook.com/UAMSnurses';
        }
    }

    if ('health' == $site) {
        if ( 'uams-aux' == uams_get_site_info()['subsite'] ) { // Example
            $social_fb = 'https://www.facebook.com/UAMSAuxiliary/';
        } else {
            $social_fb = 'https://www.facebook.com/UAMShealth/';
        }
        $social_tw = 'http://twitter.com/uamshealth';
        $social_ig = 'https://instagram.com/uamshealth/';
        $social_li = 'https://www.linkedin.com/company/uams/';
        $social_pn = 'https://www.pinterest.com/uamshealth/';
    }

    if ('inside' == $site) {
        if ( 'fitness-center' == uams_get_site_info()['subsite'] ) { // Example
            $social_fb = 'http://www.facebook.com/uamsfitnesscenter';
            $social_tw = 'https://twitter.com/UAMSfitness';
        }
    }

    // Render this by default.
    // Replace social URLs if the relevant custom fields have values.
    echo '<ul class="nav social" role="navigation" aria-label="Social media">';
    echo '<li class="nav-item"><a class="nav-link" href="'.$social_fb.'" target="_blank"><span class="fab fa-facebook"></span><span class="sr-only">Facebook</span></a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="'.$social_tw.'" target="_blank"><span class="fab fa-twitter"></span><span class="sr-only">Twitter</span></a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="'.$social_ig.'" target="_blank"><span class="fab fa-instagram"></span><span class="sr-only">Instagram</span></a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="'.$social_yt.'" target="_blank"><span class="fab fa-youtube"></span><span class="sr-only">YouTube</span></a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="'.$social_li.'" target="_blank"><span class="fab fa-linkedin"></span><span class="sr-only">LinkedIn</span></a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="'.$social_pn.'" target="_blank"><span class="fab fa-pinterest"></span><span class="sr-only">Pinterest</span></a></li>';
    echo '</ul>';

    // Render this by default
    echo '<ul class="nav legal" role="navigation">';
    echo '<li class="nav-item"><a class="nav-link" href="/disclaimer">Disclaimer</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="/termsofuse">Terms of Use</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="/privacy">Privacy</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="/sitemap">Site Map</a></li>';
    echo '</ul>';
    echo '<p class="copyright">&copy; '. date('Y') . ' University of Arkansas for Medical Sciences</p>';
}