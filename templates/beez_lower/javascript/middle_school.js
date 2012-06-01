var soundfile = new Array();

	soundfile[0] = '/templates/beez_lower/images/middle_school/sounds/ThinkCollege.mp3';
	soundfile[1] = '/templates/beez_lower/images/middle_school/sounds/MyMultimeida.mp3';
	soundfile[2] = '/templates/beez_lower/images/middle_school/sounds/Facebook.mp3';
	soundfile[3] = '/templates/beez_lower/images/middle_school/sounds/Twitter.mp3';
	soundfile[4] = '/templates/beez_lower/images/middle_school/sounds/MySiteHelp.mp3';
	soundfile[5] = '/templates/beez_lower/images/middle_school/sounds/TurnoffPopup.mp3';
	soundfile[6] = '/templates/beez_lower/images/middle_school/sounds/HP-MyAboutMe.mp3';
	soundfile[7] = '/templates/beez_lower/images/middle_school/sounds/MyDisability.mp3';
soundfile[8] = '/templates/beez_lower/images/middle_school/sounds/HP-TreasureIsland.mp3';
	soundfile[9] = '/templates/beez_lower/images/middle_school/sounds/HP-CareerIsland.mp3';
	soundfile[10] = '/templates/beez_lower/images/middle_school/sounds/HP-SocialLife.mp3';
		soundfile[11] = '/templates/beez_lower/images/middle_school/sounds/MyDisability.mp3';
	soundfile[12] = '/templates/beez_lower/images/middle_school/sounds/MyHealth.mp3';
soundfile[13] = '/templates/beez_lower/images/middle_school/sounds/MySkillsInterests.mp3';
	soundfile[14] = '/templates/beez_lower/images/middle_school/sounds/Worksheets.mp3';
		soundfile[15] = '/templates/beez_lower/images/middle_school/sounds/MyCareerMatching.mp3';
	soundfile[16] = '/templates/beez_lower/images/middle_school/sounds/MyTimeline.mp3';
soundfile[17] = '/templates/beez_lower/images/middle_school/sounds/MyCollegeTours.mp3';
	soundfile[18] = '/templates/beez_lower/images/middle_school/sounds/MyJobChoices.mp3';
	soundfile[19] = '/templates/beez_lower/images/middle_school/sounds/MyOnlineSafety.mp3';
	soundfile[20] = '/templates/beez_lower/images/middle_school/sounds/MyFeelings.mp3';
	soundfile[21] = '/templates/beez_lower/images/middle_school/sounds/MyFriends.mp3';
	soundfile[22] = '/templates/beez_lower/images/middle_school/sounds/MyFamily.mp3';
	soundfile[23] = '/templates/beez_lower/images/middle_school/sounds/MySocialSkills.mp3';
	soundfile[24] = '/templates/beez_lower/images/middle_school/sounds/MySelfAwareness.mp3';
	soundfile[25] = '/templates/beez_lower/images/middle_school/sounds/MyIEPVideo.mp3';
	soundfile[26] = '/templates/beez_lower/images/middle_school/sounds/MyIEPMaterials.mp3';
	soundfile[27] = '/templates/beez_lower/images/middle_school/sounds/MyVoice.mp3';
	soundfile[28] = '/templates/beez_lower/images/middle_school/sounds/MyID.mp3';
	soundfile[12] = '/templates/beez_lower/images/middle_school/sounds/MyHealth.mp3';
	soundfile[29] = '/templates/beez_lower/images/middle_school/sounds/MyMath.mp3';
	soundfile[30] = '/templates/beez_lower/images/middle_school/sounds/MyInterestsHobbies.mp3';
	soundfile[31] = '/templates/beez_lower/images/middle_school/sounds/MyWriting.mp3';
	soundfile[32] = '/templates/beez_lower/images/middle_school/sounds/MySpelling.mp3';
	soundfile[33] = '/templates/beez_lower/images/middle_school/sounds/MyOrganiziation.mp3';
	soundfile[34] = '/templates/beez_lower/images/middle_school/sounds/MyLocker.mp3';
	soundfile[35] = '/templates/beez_lower/images/middle_school/sounds/MyPortfolio.mp3';
	soundfile[36] = '/templates/beez_lower/images/middle_school/sounds/KidsHealth.mp3';
	soundfile[37] = '/templates/beez_lower/images/middle_school/sounds/MyHealthQuest.mp3';


function playSound(xy) {

 document.getElementById("soundBlock").innerHTML=
 "<embed src=\"" + soundfile[xy] + "\" hidden=\"true\" autostart=\"true\" loop=\"false\" volume=\"100\" />";
 }
function stopSound() {
 document.getElementById("soundBlock").innerHTML=
 "";
 }