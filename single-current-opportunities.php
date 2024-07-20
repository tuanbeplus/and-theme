<?php
/*
Single Post Template: Current Opportunities
*/

get_header();

$positions = getOpenPositions();
$program_id = get_field('stepping_into_program_id','options');
?>

<div class="col-12 current-opportunities">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-7 col-lg-3 text-content-block back-page">
                <a href="/students-jobseekers/" class="btn circle dark-red">
                    <span class="material-icons">arrow_back</span>
                    <span class="text">Students &amp; jobseekers</span>
                </a>
            </div>

            <div class="col-12 col-md-12 col-lg-6 search">
                <h1>Current Opportunities</h1>

                <form method="POST">
                    <div class="form-row">
                        <label>Industry / Career</label>
                        <select name="role">
                            <option value="Administration & Office Support">Administration & Office Support</option>
                            <option value="Agricultural and Veterinary Sciences">Agricultural and Veterinary Sciences</option>
							<option value="Art, Design, Architecture and Media">Art, Design, Architecture and Media</option>
                            <option value="Business, Human Resources and Management">Business, Human Resources and Management</option>
                            <option value="Commerce, Finance, Accounting and Economics">Commerce, Finance, Accounting and Economics</option>
                            <option value="Community Services">Community Services</option>
                            <option value="Education and Teaching">Education and Teaching</option>
                            <option value="Engineering">Engineering</option>
                            <option value="Government, Public Service and Public Policy">Government, Public Service and Public Policy</option>
                            <option value="Hospitality, Tourism and Events">Hospitality, Tourism and Events</option>
                            <option value="Information Technology and Computer Science">Information Technology and Computer Science</option>
                            <option value="Law">Law</option>
                            <option value="Marketing, Communications, Advertising and Public Relations">Marketing, Communications, Advertising and Public Relations</option>
                            <option value="Medicine, Nursing and Health Sciences">Medicine, Nursing and Health Sciences</option>
                            <option value="Psychology, Social Sciences and Humanities">Psychology, Social Sciences and Humanities</option>
                            <option value="Science, Mathematics and Environmental Science">Science, Mathematics and Environmental Science</option>
                            <option value="Any">Any</option>
                        </select>
					</div>

                    <div class="form-row">
                        <label>Location</label>
                        <select name="location">
                            <option value="All">All</option>
                            <option value="Sydney">Sydney</option>
                            <option value="Melbourne">Melbourne</option>
                            <option value="Canberra">Canberra</option>
                            <option value="Perth">Perth</option>
                            <option value="Adelaide">Adelaide</option>
                            <option value="Brisbane">Brisbane</option>
                            <option value="Darwin">Darwin</option>
                            <option value="Hobart">Hobart</option>
                            <option value="Regional">Regional</option>
                        </select>
                    </div>

                    <div class="form-row submit">
                        <input type="submit" value="Search" />
                    </div>

                </form>
            </div>

            <div class="col-12 offset-lg-3 col-md-12 col-lg-7 results">

                <?php
                  foreach($positions->records as $position):
                  ?>

                <div class="job" data-discipline="<?php echo $position->Industry_Career_Area__c; ?>" data-location="<?php echo $position->Location_Programs__c; ?>">

                    <div class="length">
                       <?php echo $position->Location_Programs__c; ?>
                    </div>

                    <div class="logo" style="visibility: hidden;">
                        <img src="<?php echo AND_IMG_URI. 'examp.jpg' ?>" alt="Example"/>
                    </div>

                    <div class="title">
                        <h3><?php echo $position->Name; ?></h3>
                    </div>

                    <div class="detail">
                        <p>Organisation Name: <?php echo $position->Organisation_Name_Apex__c; ?></p>
                        <p>Industry / Career: <?php echo $position->Industry_Career_Area__c; ?></p>
                        <p>Position ID: <?php echo $position->Position_ID__c; ?></p>
                        <p>Degree / Diciplines: <?php echo $position->DegreeDisciplines__c; ?></p>
                        <p>Citizenship requirements:  <?php echo $position->Candidate_Citizenship_Requirements__c !== null ? $position->Candidate_Citizenship_Requirements__c  : 'N/A'; ?></p>
                        <p>Year of study: <?php echo $position->Preferred_Year_of_Study__c !== null ? $position->Preferred_Year_of_Study__c  : 'N/A'; ?></p>
                    </div>
<!--
                    <div class="sub">
                        Plus Fitness
                    </div> -->

                    <div class="description">
                        <a href="<?php echo $position->Position_Description_Public_URL__c; ?>" target="_blank" style="color:#663077;">View job description</a>
                    </div>

                    <a href="https://andau.force.com/forms/s/andforms?formtype=stepping_into_application&programid=<?php echo $program_id; ?>" target="_blank" class="cta" style="display: inline-block;
                      color: #fff;
                      padding: 10px 25px;
                      background: #663077;
                      font-size: 16px;
                      margin: 0;
                      border-radius: 100px;
                      text-decoration: none;">
                        Apply now
                    </a>

                </div>
                <?php endforeach; ?>

            </div>

        </div>
    </div>
</div>

<section class="col-12 back-page">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6 text-content-block">
                <a href="/students-jobseekers/" class="btn circle dark-red">
                    <span class="material-icons">arrow_back</span>
                    <span class="text">Students &amp; jobseekers</span>
                </a>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
