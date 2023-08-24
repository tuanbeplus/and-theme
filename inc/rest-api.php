<?php

//The Following registers an api route with multiple parameters.
add_action( 'rest_api_init', 'andorgau_rest_api_all_posts');

function andorgau_rest_api_all_posts(){
    register_rest_route( 'wp/v2', '/all_posts/', array(
        'methods' => 'GET',
        'callback' => 'andorgau_get_all_posts',
    ));
}

function andorgau_get_all_posts($request){

  $args = array(
    'post_type' => 'any',
    'post_status' => 'publish',
    'posts_per_page' => 10,
    's' => $_GET['keysearch'],
    // 'orderby' => 'title',
    // 'order' => 'ASC'
  );

  //Results search
  $the_posts = get_posts( $args );
  $result = [];

  //add url to array
  foreach ($the_posts as $key => $post) {
    $result[$key]['id'] = $post->ID;
    $result[$key]['value'] = $post->post_title;
    $result[$key]['label'] = $post->post_title;
    $result[$key]['url'] = get_permalink($post->ID);
  }

  return $result;

}

/**
 * Add a sidebar
 */
function andorgau_add_sidebar_init() {
    // Add a sidebar for search page
    register_sidebar( array(
        'name'          => __( 'Sidebar Search', 'and' ),
        'id'            => 'sidebar-search',
        'description'   => __( 'Widgets in this area will be shown on search page.', 'and' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>',
    ) );
    // Add a sidebar for Dashboard page
    register_sidebar( array(
        'name'          => __( 'Dashboard Advertisement', 'and' ),
        'id'            => 'dashboard_advertisement',
        'description'   => __( 'Widgets in this area will be shown on search page.', 'and' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'andorgau_add_sidebar_init' );

/**
* Shortcode show recent posts
*/


add_shortcode( 'andorgau_list_recent_posts' , 'andorgau_list_recent_posts_template');
function andorgau_list_recent_posts_template($atts){
    $atts = shortcode_atts( array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'post__in' => ''
    ), $atts, 'andorgau_list_recent_posts' );

    if(!empty($atts['post__in'])){
      $post_in = explode(',',$atts['post__in']);
      $atts['post__in'] = $post_in;
    }

    $feedDatas = get_posts($atts);

    if(!empty($feedDatas)):

    $feedDataHtml = '';
    foreach($feedDatas as $feedData) {
        $feedImage = get_the_post_thumbnail_url($feedData->ID);

        $feedDataHtml .= '<li class="col-md-12 the-card">
            <div class="inner">
                <img src="'.$feedImage.'" alt="feed image"/>
                <div class="detail">
                    <h2><a href="'.get_permalink($feedData->ID).'">'.get_the_title($feedData->ID).'</a></h2>
                    <p>'.get_the_excerpt($feedData->ID).'</p>
                </div>
                <div class="btn circle">
                    <span class="material-icons">arrow_forward</span>
                </div>
            </div>
        </li>';

    }

    echo '<section class="page-tiles">
                    <div class="row ">
                        <div class="col-12 cards tiles">
                            <ul class="row">
                                '.$feedDataHtml.'
                            </ul>
                        </div>
                    </div>
            </section>';

    endif;

}

/**
 * refresh Salesforce access token when it expired
 * 
 */
function sf_refresh_token($response_error) {

	if (isset($response_error[0]->errorCode)) {

		$sf_site_url = get_field('salesforce_site_url', 'option');
		$sf_client_id = get_field('salesforce_client_id', 'option');
		$sf_client_secret = get_field('salesforce_client_secret', 'option');
		$sf_refresh_token = get_field('salesforce_api_refresh_token', 'option');

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $sf_site_url .'/services/oauth2/token',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array(
				'client_id' => $sf_client_id,
				'client_secret' => $sf_client_secret,
				'grant_type' => 'refresh_token',
				'refresh_token' => $sf_refresh_token,
			),
			CURLOPT_HTTPHEADER => array(
				'Cookie: BrowserId=oe5jPfO4Ee2pBweEgW6i5Q; 
				CookieConsentPolicy=0:0; 
				LSKey-c$CookieConsentPolicy=0:0'
			),
		));

		$response = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($response);

		if (isset($response->access_token)) {
			update_field('salesforce_api_access_token', $response->access_token, 'option');
		}
	}
}

/**
 * get Salesforce object data by SOQL Query
 * 
 */
function sf_query_object_metadata($sql) {

	$sf_access_token = get_field('salesforce_api_access_token', 'option');
	$sf_endpoint_url = get_field('salesforce_endpoint_url', 'option');
	$sf_api_ver = get_field('salesforce_api_version', 'option');
	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => $sf_endpoint_url.'/services/data/'.$sf_api_ver.'/query/?q='. urlencode($sql),
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => array(
			'Authorization: Bearer '.$sf_access_token,
			'Cookie: BrowserId=gRbOFTL-Eey5HuWIRJTEZw; CookieConsentPolicy=0:1; LSKey-c$CookieConsentPolicy=0:1'
		),
	));

	$response = curl_exec($curl);
	curl_close($curl);
	$response = json_decode($response);

	if (isset($response->records)) {
		return $response;
	}
	elseif (isset($response[0]->errorCode)) {
		if ($response[0]->errorCode == 'INVALID_SESSION_ID') {
			sf_refresh_token($response);
		}
	}
}

/**
 * get Salesforce object data by object name & ID
 * 
 */
function sf_get_object_metadata($obj_name, $obj_id) {

	$sf_access_token = get_field('salesforce_api_access_token', 'option');
	$sf_endpoint_url = get_field('salesforce_endpoint_url', 'option');
	$sf_api_ver = get_field('salesforce_api_version', 'option');
	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => $sf_endpoint_url.'/services/data/'.$sf_api_ver.'/sobjects\/'. $obj_name .'/'. $obj_id,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => array(
			'Authorization: Bearer '.$sf_access_token,
			'Cookie: BrowserId=gRbOFTL-Eey5HuWIRJTEZw; CookieConsentPolicy=0:1; LSKey-c$CookieConsentPolicy=0:1'
		),
	));

	$response = curl_exec($curl);
	curl_close($curl);
	$response = json_decode($response);

	if (isset($response->records)) {
		return $response;
	}
	elseif (isset($response[0]->errorCode)) {
		if ($response[0]->errorCode == 'INVALID_SESSION_ID') {
			sf_refresh_token($response);
		}
	}
}

function getOurMembers($type)
{
	$sql = "SELECT name FROM Account WHERE Type = 'Member' AND Membership_Level__c = '". $type ."' ORDER BY name ASC";

	$members = sf_query_object_metadata($sql);

	$html = '<ul>';
	foreach ($members->records as $member) {
		if ($member->Name !== 'Australian Network on Disability (AND)') {
			$html .= '<li>' . $member->Name . '</li>';
		}
	}
	$html .= '</ul>';

	return $html;
}

function getEvents($type = false)
{
	$sql = "SELECT Name, Type, Event_Date__C, StartDate, Event_Type__c, Event_Location__c, Event_Time__c, IsActive 
			FROM Campaign 
			WHERE IsActive = true 
			AND Event_Date__C != null 
			ORDER BY StartDate ASC";

	$events = sf_query_object_metadata($sql);

	$html = '';
	$i = 0;

	foreach ($events->records as $event) {

		$date_now = strtotime("now");
		$event_date = strtotime($event->StartDate);

		if ($event_date >= $date_now) {

			$is_location_exist_link = str_contains($event->Event_Location__c, 'http');

			if ($is_location_exist_link) {
				$event_before_link = strstr($event->Event_Location__c, 'https', true);
				$event_link = strstr($event->Event_Location__c, 'https');

				$event_location = $event_before_link .' <a href="'. $event_link .'" target="_blank"> '. $event_link .'</a>';
			}
			else {
				$event_location = $event->Event_Location__c;
			}

			if ($type == 'dashboard') {
				$html .= '<li class="event">
                            <div class="event-info">
                                <p><span>Campaign: </span>' . $event->Name . '</p>
								<p><span>Date: </span><span>' . $event->Event_Date__c . '</span></p>
								<p><span>Time: </span><span> ' . $event->Event_Time__c . '</span></p>
								<p><span>Location: </span>'. $event_location .'</p>
                            </div>
                        </li>';
				if ($i++ > 4) break;
			} else {

				if ($event->Type == 'Webinar' && $type == 'webinars') {
					
					$html .= '<div class="col-md-12 col-lg-6 ' . $cardClass . ' the-card">
						<div class="inner">
							<div class="detail">
								<h3>' . $event->Type . '</h3>
								<p>' . $event->Name . '</p>
								<p><span>Event Type:</span> ' . $event->Event_Type__c . '</p>
								<p><span>Date:</span> ' . $event->Event_Date__c . '</p>
								<p><span>Time:</span> ' . $event->Event_Time__c . '</p>
								<p><span>Location: </span>'. $event_location .'</p>
							</div>
						</div>
					</div>';
				}

				if ($event->Type == 'Meeting' && $type == 'roundtables') {
					$html .= '<div class="col-md-12 col-lg-6 ' . $cardClass . ' the-card">
						<div class="inner">
							<div class="detail">
								<h3>' . $event->Type . '</h3>
								<p>' . $event->Name . '</p>
								<p><span>Event Type:</span> ' . $event->Event_Type__c . '</p>
								<p><span>Date:</span> ' . $event->Event_Date__c . '</p>
								<p><span>Time:</span> ' . $event->Event_Time__c . '</p>
								<p><span>Location: </span>'. $event_location .'</p>
							</div>
						</div>
					</div>';
				}

				if ($type == 'all') {
					$html .= '<div class="col-md-12 col-lg-6 ' . $cardClass . ' the-card">
						<div class="inner">
							<div class="detail">
								<h3>' . $event->Type . '</h3>
								<p>' . $event->Name . '</p>
								<p><span>Event Type:</span> ' . $event->Event_Type__c . '</p>
								<p><span>Date:</span> ' . $event->Event_Date__c . '</p>
								<p><span>Time:</span> ' . $event->Event_Time__c . '</p>
								<p><span>Location: </span>'. $event_location .'</p>
							</div>
						</div>
					</div>';
				}
			}
		}
	}

	return $html;
}

function getOpenPositions()
{
	$stepping_into_program_id = get_field('stepping_into_program_id', 'options');
	
	$sql = "SELECT Name,Organisation_Name_Apex__c,URL_for_Stepping_Into_Position__c,Industry_Career_Area__c,
				Location_Programs__c,Candidate_Citizenship_Requirements__c,Preferred_Year_of_Study__c,
				Position_ID__c,Position_Description_Public_URL__c,Position_Display__c,DegreeDisciplines__c 
			FROM Position__c 
			WHERE Program__c = '".$stepping_into_program_id."' 
			AND Position_Display__c=true";

	$response = sf_query_object_metadata($sql);

	return $response;
}

function getUpcomingPrograms()
{
	$stepping_into_program_id = get_field('stepping_into_program_id', 'options');
	$sql = "SELECT Name,Start_Date__c FROM Program__c";

	$response = sf_query_object_metadata($sql);

	return $response;
}

function getContacts($contact_id = '')
{
  	$sql = "SELECT FIELDS(ALL) FROM Contact WHERE Id = '".$contact_id."' LIMIT 200";

	$response = sf_query_object_metadata($sql);

  	return $response;
}

function getOpportunity()
{
  	$account_id = getUser($_COOKIE['userId'])->records[0]->AccountId;

  	$sql = "SELECT FIELDS(ALL) 
			FROM Opportunity 
			WHERE RecordTypeId='0120I000000TONeQAO' 
			AND IsClosed=false 
			AND AccountId='".$account_id."' 
			ORDER BY CreatedDate DESC
			LIMIT 200";

	$response = sf_query_object_metadata($sql);
	
	return $response;
}

function getAllOpportunity()
{
  	$sql = "SELECT Id, AccountId, Name, StageName, CloseDate, Contact__c 
			FROM Opportunity 
			ORDER BY CreatedDate DESC";

	$response = sf_query_object_metadata($sql);

	return $response;
}

function getElearns()
{
	$sql = "SELECT FIELDS(ALL) FROM eLearn__c WHERE Status__c = 'Active' LIMIT 200" ;

	$response = sf_query_object_metadata($sql);

	return $response;
}

function getElearnsByOpportunityId($opportunity_id)
{
	$sql = "SELECT FIELDS(ALL) FROM eLearn__c WHERE Status__c = 'Active' AND Opportunity__c = '". $opportunity_id ."' LIMIT 200" ;

	$response = sf_query_object_metadata($sql);

	return $response;
}

function getTasks()
{
  	$sql = "SELECT FIELDS(ALL) FROM Task WHERE OwnerId = '".$_COOKIE['userId']."' ORDER BY CompletedDateTime DESC LIMIT 200";
  	
	$response = sf_query_object_metadata($sql);

	return $response;
}

function getUser($userId = '')
{
  	$sql = "SELECT FIELDS(ALL) FROM User WHERE Id = '". $userId ."' LIMIT 200";

	$response = sf_query_object_metadata($sql);

	return $response;
}

function getUserByEmail($email)
{
  	$sql = "SELECT FIELDS(ALL) FROM User WHERE Email = '". $email ."' LIMIT 200";

	$response = sf_query_object_metadata($sql);

	return $response;
}

function getCampains()
{
  	$sql = "SELECT FIELDS(ALL) FROM Campaign WHERE IsActive = true AND StartDate != NULL ORDER BY StartDate ASC LIMIT 200";

	$response = sf_query_object_metadata($sql);

	return $response;
}

function getOpportunityContact($contact_id='')
{
  	$sql = "SELECT Id, Contact__c FROM Opportunity WHERE Contact__c ='".$contact_id."'";

	$response = sf_query_object_metadata($sql);

	return $response;
}

function getAccountMember()
{
  	$account_id = getUser($_COOKIE['userId'])->records[0]->AccountId;

  	$sql = "SELECT FIELDS(ALL) FROM Account WHERE Id = '". $account_id ."'  LIMIT 200";

	$response = sf_query_object_metadata($sql);

	return array(
		'Id'  => $response->records[0]->Id,
		'Name' => $response->records[0]->Name,
		'manager' => $response->records[0]->OwnerId,
		'tasks' => array(
			'network' => $response->records[0]->Maintains_an_Employee_Network__c,
			'action_plan' => $response->records[0]->Accessibility_Action_Plan_in_place__c,
			'workplace' => $response->records[0]->Workplace_Adjustment_Policy_or_Procedure__c,
			'review' => $response->records[0]->Recruitment_Review__c
		),
		'manager_phone' => $response->records[0]->Organisation_Owner_Phone__c,
		'manager_email' => $response->records[0]->Organisation_Owner_Email__c,
		'hours_remain' => $response->records[0]->Memb_Hours_Remain_Org__c,
		'renewal' => $response->records[0]->Membership_Renewal_Month__c,
		'membership_level' => $response->records[0]->Membership_Level__c,
		'membership_status' => $response->records[0]->Membership_Status__c,
	);
}

// Add Shortcode Create Programs form CTA button
function get_sf_programs_form_cta($atts, $content = null) {

    $default = array(
      'formtype' => '#',
      'content' => 'CTA Content',
    );
    $a = shortcode_atts($default, $atts);

    if ($a['formtype'] == 'stepping_position' || $a['formtype'] == 'stepping_into_application') {
      $program_id = get_field('stepping_into_program_id', 'options');
    }
    elseif ($a['formtype'] == 'mentor_application') {
      $program_id = get_field('pace_program_id', 'options');
    }
    $content = do_shortcode($content);
    return '<p><a class="cta" href="https://andau.force.com/forms/s/andforms?formtype=' .$a['formtype']. '&programid=' .$program_id. '" >'.$a['content'].'</a></p>';
}
add_shortcode('sf_programs_form_cta', 'get_sf_programs_form_cta');

// get eLearn modules purchased for Dashboard and eLearn overview
function get_elearn_modules_purchased($modules, $bundles)
{
	if ($modules['All_Modules__c'] == true ) {
		echo '<span>All Modules</span><span>, </span>';
	}
	else {
		// Module #1
		if ($modules['About_Disability_Accessibility__c'] == true) { 
			echo '<span>About Disability & Accessibility</span><span>, </span>';
		}
		// Module #2
		if ($modules['Disability_Confidence_is_Good_Business__c'] == true) { 
			echo '<span>Disability Confidence is Good Business</span><span>, </span>';
		}
		// Module #3
		if ($modules['Inclusive_Communication__c'] == true) { 
			echo '<span>Inclusive Communication</span><span>, </span>';
		}
		// Module #4
		if ($modules['Creating_Enabling_Environments__c'] == true) { 
			echo '<span>Creating Enabling Environments</span><span>, </span>';
		}
		// Module #5
		if ($modules['Inclusive_Recruitment__c'] == true) {
			echo '<span>Inclusive Recruitment</span><span>, </span>';
		}
		// Module #6
		if ($modules['Workplace_Adjustments__c'] == true) {
			echo '<span>Workplace Adjustments</span><span>, </span>';
		}
		// Module #7
		if ($modules['Disability_Confident_Conversations__c'] == true) {
			echo '<span>Disability Confident Conversations</span><span>, </span>';
		}
		// Module #8
		if ($modules['Facilitating_Positive_Employment__c'] == true) {
			echo '<span>Facilitating Positive Employment</span><span>, </span>';
		}
		// Module #9
		if ($modules['Inclusive_Customer_Experiences__c'] == true) {
			echo '<span>Inclusive Customer Experiences</span><span>, </span>';
		}
		// Module #10
		if ($modules['The_Experience_Journey__c'] == true) {
			echo '<span>The Experience Journey</span><span>, </span>';
		}
		// Module #11
		if ($modules['Your_Disability_Confidence__c'] == true) {
			echo '<span>Your Disability Confidence</span><span>, </span>';
		}
		// Bundles
		if ($bundles['General_Workforce_Bundle__c'] == true) {
			echo '<span>General Workforce Bundle</span><span>, </span>';
		}
		if ($bundles['Recruiters_HR_Bundle__c'] == true) {
			echo '<span>Recruiters / HR Bundle</span><span>, </span>';
		}
		if ($bundles['Managers_Bundle__c'] == true) {
			echo '<span>Managers Bundle</span><span>, </span>';
		}
		if ($bundles['Inclusive_Customer_Experiences_Bundle__c'] == true) {
			echo '<span>Inclusive Customer Experiences Bundle</span><span>, </span>';
		}
		if ($bundles['DCR_Program_Bundle__c'] == true) {
			echo '<span>DCR Program Bundle</span><span>, </span>';
		}
	}
}

function sf_query_obj_data_test($query_url)
{
	$access_token = '00D28000001Ez0g!AQEAQKw_NNk99FsQ9KkYCSvPEWQe0DdqLGy2fAKz_RjsiZO7ebYZ7qWHUZY44VTgTvfCRHP4Q4fkfGEw94q3Z6dYhLvC306h';
	$curl = curl_init();

  	curl_setopt_array($curl, array(
		CURLOPT_URL => $query_url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => array(
			'Authorization: Bearer '.$access_token,
			'Cookie: BrowserId=gRbOFTL-Eey5HuWIRJTEZw; CookieConsentPolicy=0:1; LSKey-c$CookieConsentPolicy=0:1'
		),
	));

	$response = curl_exec($curl);
	curl_close($curl);
	$response = json_decode($response);

	return $response;
}

function getObjTest() {

	$sf_endpoint_url = 'https://andau.my.salesforce.com';
	$sf_api_ver = get_field('salesforce_api_version', 'option');
	$sql = "SELECT Id FROM Contact";

	$query_url = $sf_endpoint_url.'/services/data/'.$sf_api_ver.'/query/?q='. urlencode($sql);

	$response = sf_query_obj_data_test($sf_endpoint_url.'/services/data/v53.0/query/0r8xx233InIG28yAID-4000');
	return $response;

	if(isset($response->nextRecordsUrl)) {
		$response_arr = array();
		// $response_arr[0] = $response->records;  // Save first 2000 records to array
		$count = ceil($response->totalSize/ 2000);

		for ($i=1; $i < $count; $i++) { 
			$next_records_url = substr($response->nextRecordsUrl, 1, strpos($response->nextRecordsUrl, '-'));
			$query_url = $sf_endpoint_url.$next_records_url.($i*2000);
			$response_arr[$i] = sf_query_obj_data_test($query_url)->records;
			print_r($query_url);
			break;
		}

		// return $response_arr;

	}
	else {
		return $response;
	}
}