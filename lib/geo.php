<?php
/*
 * Custom Geo Functions
 * 
 * 
 * 
 */
// Define region cities
global $region_northwest, $region_northeast, $region_southwest, $region_southeast, $region_central;
$region_northwest = array(
    'Fayetteville','Lowell','Springdale','Rogers','Bentonville','Alma','Barling','Bella Vista','Berryville','Booneville','Branch','Center Ridge','Charleston','Clarksville','Clinton','Compton','Danville','Dardanelle','Decatur','Elm Springs','Eureka Springs','Fairfield Bay','Farmington','Flippin','Fort Smith','Gassville','Gentry','Aaron','Gravette','Greenwood','Hackett','Harrison','Hartford','Hattieville','Hector','Huntsville','Jasper','Lavaca','Lead Hill','Leslie','London','Mansfield','Marshall','Morrilton','Mountain Home','Mountainburg','Norfork','Ozark','Paris','Pea Ridge','Plainview','Plumerville','Pottsville','Prairie Grove','Russellville','Aaronsburg','Scotland','Siloam Springs','Subiaco','Tontitown','Valley Springs','Van Buren','Waldron','West Fork','Winslow','Yellville','Lincoln',
);
$region_northeast = array(
    'Jonesboro','Armorel','Ash Flat','Augusta','Bald Knob','Batesville','Bay','Beebe','Blytheville','Bradford','Brockwell','Brookland','Burdette','Calico Rock','Cave City','Charlotte','Cherokee Village','Corning','Dyess','Floral','Guion','Hardy','Harrisburg','Heber Springs','Higden','Hoxie','Imboden','Joiner','Keiser','Kensett','Lepanto','Luxora','Lynn','Mammoth Spring','Marion','Marked Tree','Marmaduke','Maynard','Mccrory','Mcrae','Melbourne','Mount Pleasant','Mountain View','Newark','Newport','Osceola','Paragould','Piggott','Pocahontas','Quitman','Rector','Romance','Salem','Searcy','Sulphur Rock','Swifton','Timbo','Trumann','Walnut Ridge','Weiner','West Memphis','Wiseman','Wynne'
);
$region_southwest = array(
    'Amity','Arkadelphia','Ashdown','Camden','Carthage','De Queen','Eldorado','Fordyce','Friendship','Gillham','Glenwood','Hope','Lockesburg','Magnolia','Malvern','Mena','Mount Holly','Mount Ida','Nashville','Norman','Prescott','Rosston','Smackover','Stamps','Texarkana','Waldo','Washington','Hampton','Hatfield'
);
$region_southeast = array(
    'Altheimer','Brinkley','Clarendon','Crossett','Dewitt','Dermott','Des Arc','Dumas','Forrest City','Fountain Hill','Gould','Grady','Hamburg','Hazen','Humphrey','Lake Village','Marianna','Mcgehee','Monticello','Palestine','Pinebluff','Poplar Grove','Rison','Star City','Stuttgart','Warren','Whitehall','Helena','West Helena'
);
$region_central = array(
    'Little Rock Air Force Base','North Little Rock','Bryant','Benton','Little Rock','Adona','Alexander','Bauxite','Bigelow','Cabot','Carlisle','Conway','England','Enola','Greenbrier','Hot Springs Village','Jacksonville','Jessieville','Lonoke','Maumelle','Mount Vernon','Pearcy','Sheridan','Sherwood','Vilonia','Ward','Wrightsville','Hot Springs','Hot Springs National Park'
);
// Function include
function is_in_region( array $regions ) {
    global $region_northwest, $region_northeast, $region_southwest, $region_southeast, $region_central;
    $geo = geoip_detect2_get_info_from_current_ip();
    $city = $geo->city->name;
    $state = $geo->subdivisions[0]->name;

    if('Arkansas' !== $state) {
        return false;
    }

    foreach($regions as $region){
        if ($region == 'northwest') {
            if (in_array($city, $region_northwest)) {
                return true;
            }
        } elseif($region == 'northeast') {
            if (in_array($city, $region_northeast)) {
                return true;
            }
        } elseif($region == 'southwest') {
            if (in_array($city, $region_southwest)) {
                return true;
            }
        } elseif($region == 'southeast') {
            if (in_array($city, $region_southeast)) {
                return true;
            }
        } elseif($region == 'central') {
            if (in_array($city, $region_central)) {
                return true;
            }
        } else {
            return false;
        }
    }
    
}
// Function exclude
function is_not_in_region( array $regions ) {
    global $region_northwest, $region_northeast, $region_southwest, $region_southeast, $region_central;

    $geo = geoip_detect2_get_info_from_current_ip();
    $city = $geo->city->name;
    $state = $geo->subdivisions[0]->name;

    if('Arkansas' !== $state) {
        return true;
    }
    
    $display_region = true;
    foreach($regions as $region){
        if ($region == 'northwest') {
            if (in_array($city, $region_northwest)) {
                $display_region = false;
                // return false;
            }
        } elseif($region == 'northeast') {
            if (in_array($city, $region_northeast)) {
                $display_region = false;
                // return false;
            }
        } elseif($region == 'southwest') {
            if (in_array($city, $region_southwest)) {
                $display_region = false;
                // return false;
            }
        } elseif($region == 'southeast') {
            if (in_array($city, $region_southeast)) {
                $display_region = false;
                // return false;
            }
        } elseif($region == 'central') {
            if (in_array($city, $region_central)) {
                $display_region = false;
                // return false;
            }
        } else {
            // $display_region = true;
            // return true;
        }
    }
    return $display_region;
    
}