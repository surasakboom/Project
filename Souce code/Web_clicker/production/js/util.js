myapp.controller('utilCtrl', ['$scope', '$http', '$cookies', '$window', '$interval', '$timeout', function($scope, $http, $cookies, $window, $interval, $timeout){

	$scope.setYear = function(){
		var date = new Date();
		console.log('month: '+date.getMonth()+' year : '+date.getYear());
		if(date.getMonth() <=6){
			$scope.year = date.getFullYear() + 542;
		}else{
			$scope.year = date.getFullYear() + 543;
		}
		console.log($scope.year);
		
	}

	$scope.getUserName = function(){
		$http.get('php/getUser.php?q=get_user')
		.success(function(data){
			$scope.prefix = data.Prefix;
			$scope.firstname = data.FirstName;
			$scope.lastname = data.LastName;
			$scope.shortname = data.ShortName;
			$scope.username = data.UserName;
			$scope.user = data.UserName;
			$scope.password = data.Password;
			$scope.repassword = data.Password;
			$scope.email = data.Email;
			$scope.user_img = data.Image;
			$scope.student_id = parseInt(data.Student_ID);
			$scope.id = data.id;
			console.log(data);
		});
	}

	$scope.getSubject = function(grade, year){
		$scope.display();
		$http.get("php/subject.php?q=student_get_subject&class="+grade+"&year="+year)
		.success(function(data){
			console.log(data);
			$scope.subjects = data;
		}).error(function(data) {
			/* Act on the event */
		});
	}

	$scope.display = function(){
		$scope.show = !$scope.show;
	}

	$scope.isRegister = function(){
		$http.get("php/getRegister.php?q=isregis&section_id="+0).success(function(data){
			console.log(data);
			if(data){
				return true;
				//$scope.isRegist = true;
			}
			else{
				return false;
				//$scope.isRegist = false;
			}
		})
	}

	$scope.register = function(id){
		if(confirm("คุณต้องการลงทะเบียนวิชานี้ใช่ไหม")){
			$http.get("php/register.php?q=register&section_id="+$scope.subjects[id].id)
			.success(function(data){
				console.log(data);
				if(data=='success'){
					$scope.subjects[id].IsRegist = true;
				}
			});
		}
	}

	$scope.getTeacherSubject = function(){
		$http.get('php/subject.php?q=teacher_get_subject')
		.success(function(data){
			console.log(data);
			$scope.teacher_subjects = data;
		});
		console.log('outside http');
	}

	$scope.goto = function(path){
		window.location.href = path;
	}

	$scope.setSection = function(sid, sname){
		$cookies.put("sid", sid);
		$cookies.put('subject_name', sname);
		$scope.goto("quiz.php");
	}

	$scope.getQuiz = function(){
		var sid = $cookies.get('sid');
		$http.get('php/quiz.php?q=get_quiz&sid='+sid)
		.success(function(data){
			console.log(data);
			$scope.quizes = data;

		});
	}

	$scope.createQuiz = function(){
		var sid = $cookies.get('sid');
		console.log($scope.quizes.length);
		if($scope.quiz_name != undefined){
			$http.get('php/quiz.php?q=create_quiz&qname='+$scope.quiz_name+'&sid='+sid)
			.success(function(data){
				console.log(data);
				if(data == 'error'){ alert('Failed to Create Quiz!'); return;}
				if($scope.quizes.length==0){
					$scope.quizes = [];
				}
				$scope.quizes.push(data);
				$('#myModal').modal('hide');
			});
		}
	}

	$scope.setQuiz = function(qid){
		$cookies.put('quiz_id', qid);
		$scope.goto('question.php');
	}

	$scope.editQuiz = function(quiz){
		$cookies.put('quiz_id', quiz.id);
		$scope.quiz_name = quiz.Quiz_Name;
	}

	$scope.updateQuiz = function(){
		if($scope.quiz_name != undefined){
			var quiz_id = $cookies.get('quiz_id');
			$http.get('php/quiz.php?q=edit_quiz&quiz_name='+$scope.quiz_name+'&quiz_id='+quiz_id)
			.success(function(data){
				console.log(data);
				$('#editModal').modal('hide');
				$scope.goto('quiz.php');
			});
		}
	}

	$scope.deleteQuiz = function(quiz_id){
		if(confirm("คุณต้องการลบชุดคำถามนี้ใช่ไหม")){
			$http.get('php/quiz.php?q=delete_quiz&quiz_id='+quiz_id)
				.success(function(data){
					console.log(data);
					$scope.goto('quiz.php');
				});
		}
	}

	var changePage = false;
	$scope.getQuestion = function(){
		var qid = $cookies.get('quiz_id');
		var sid = $cookies.get('sid');
		$http.get('php/quiz.php?q=get_question_list&qid='+qid+'&sid='+sid+'&question_id=')
		.success(function(data){
			console.log(data);
			var range = [];
			for(var i=0;i<data.length;i++) {
			  range.push(i);
			}
			$scope.range = range;
			$scope.questions = data;
			$scope.page = $cookies.get('clicked-page');
			//$cookies.put('questionid', "");
			//$cookies.put('questionid', $scope.questions[0].id);
		});
	}

	$scope.getQuestionForQuiz = function(){
		var qid = $cookies.get('quiz_id');
		var sid = $cookies.get('sid');
		$http.get('php/quiz.php?q=get_question_display&qid='+qid+'&sid='+sid)
		.success(function(data){
			console.log(data);
			var range = [];
			for(var i=0;i<data.length;i++) {
			  range.push(i);
			}
			$scope.range = range;
			$scope.questions = data;
			$scope.page = $cookies.get('clicked-page');
			//$cookies.put('questionid', "");
			$cookies.put('questionid', $scope.questions[0].id);
		});
	}

	$scope.createQuestion = function(){
		if(checkFormValid()){
			var form = document.getElementById('demo');
			var fd = new FormData(form);
			var qid = $cookies.get('quiz_id');
			fd.append('qid', qid);
			$http.post('php/quiz.php?q=create_question&qid='+$scope.qid, fd, {
                transformRequest:angular.identity,
                headers:{'Content-Type':undefined}
            }).success(function(data){
					console.log(data);
					if(data == 'success'){
						$scope.goto('question.php');
					}
			});

		}
	}

	$scope.deleteQuestion = function(qid){
		if(confirm("คุณต้องการลบคำถามนี้ใช่ไหม")){
			$http.get('php/quiz.php?q=delete_question&qid='+qid)
			.success(function(data){
				$window.location.reload();
			});
		}
	}

	$scope.setQuestion = function(qid, index){
		$cookies.put('questionid', qid);
		$cookies.put('clicked-page', index);
		$cookies.put('realtime', 'true');
		$cookies.put('change-page', '');
		console.log("qid"+qid);
    	// var myWindow = window.open("display_question.php", "", "width=1000,height=1000");
    	$scope.goto('answer.php');
	}

	$scope.getQuestionById = function(){
		var qid = $cookies.get('questionid');
		var sid = $cookies.get('sid');
		$http.get('php/quiz.php?q=get_question_by_id&qid='+qid+'&sid='+sid)
		.success(function(data){
			console.log(data);
			$scope.question = data;
			$scope.answer = '2';

			$scope.checkboxSelection = '1';
		});

	}

	$scope.editQuestion = function(id){
		$cookies.put('questionid', id);
		$scope.goto('edit_quiz.php');
	}

	$scope.updateQuestion = function(){
		if(checkFormValid()){
			var form = document.getElementById('demo');
			var fd = new FormData(form);
			var qid = $cookies.get('quiz_id');
			var question_id = $cookies.get('questionid');
			fd.append('qid', qid);
			$http.post('php/quiz.php?q=update_question&question_id='+question_id, fd, {
                transformRequest:angular.identity,
                headers:{'Content-Type':undefined}
            }).success(function(data){
					console.log(data);
					if(data == 'success'){
						$scope.goto('question.php');
					}
			});

		}
	}

	$scope.displayQuiz = function(qid,index){
		$cookies.put('quiz_id', qid);
		//$cookies.put('questionid', qid);
		$cookies.put('clicked-page', index);
		$cookies.put('realtime', 'true');
		$cookies.put('change-page', '');
		var myWindow = window.open("display_question.php", "", "width=1000,height=1000");
	}

	$scope.displayScorePass = function(){
		// $cookies.put('quiz_id', quizid);
		// $cookies.put('questionid', questionid);
		var myWindow = window.open("display_score.php", "", "width=1000,height=500");
	}

	$scope.getStudentScorePass = function(){
		var sid = $cookies.get('sid');
		var quizid = $cookies.get('quiz_id');
		var questionid = $cookies.get('questionid');
		console.log('quiz '+questionid+' questionid '+questionid);
		$http.get('php/score.php?q=get_score_pass&quizid='+quizid+'&questionid='+questionid+'&sectionid='+sid)
		.success(function(data){
			console.log(data);
			$scope.students = data;
		}).error(function(){ });
	}

	function checkFormValid(){
		if($scope.demo.question.$valid&&$scope.demo.choice1.$valid&&$scope.demo.choice2.$valid&&$scope.demo.choice3.$valid&&$scope.demo.choice4.$valid&&$scope.demo.description.$valid&&$scope.demo.point.$valid&&$scope.demo.lecture.$valid){ return true; }
		return false;
	}

	$scope.pageActive = { 'background-color':'#d3d3d3'}

	$scope.pageIsActive = function(p){
		if(p == $scope.page) return $scope.pageActive;
		else return null;
	}

	$scope.setPage = function(p){
		console.log(p);
		$scope.page = p;
		$cookies.put('clicked-page', p);
		var sid = $cookies.get('sid');
		$cookies.put('questionid',$scope.questions[p].id);
		$http.get('php/quiz.php?q=set_current_question&question_id='+$scope.questions[p].id+"&sid="+sid)
		.success(function(data){
			console.log(data);
		});
	}

	$scope.nextPage = function(p){
		p++;
		if(p>=$scope.questions.length) p = $scope.questions.length-1;
		$scope.setPage(p);
	}

	$scope.prevPage = function(p){
		p--;
		if(p<0) p = 0;
		$scope.setPage(p);
	}

	$scope.editSubject = function(subject){
		$cookies.put('section_id', subject.id)
		$cookies.put('subject_name', subject.Subject_Name);
		$scope.goto('edit_subject.php');
	}

	$scope.retriveSubject = function(){
		var sectionId = $cookies.get('section_id');
		console.log(sectionId);
		$scope.subjectName = $cookies.get('subject_name');
		$http.get('php/subject.php?q=get_subject_by_id&section_id='+sectionId)
		.success(function(data){
			console.log(data);
			$scope.subject = data;
			$scope.TimeStart = Date.parseExact(setTime(data.TimeStart), "hh:mm tt");
			$scope.TimeEnd = Date.parseExact(setTime(data.TimeEnd), "hh:mm tt");
			console.log(setTime(data.TimeEnd)+" "+setTime(data.TimeStart)+" "+$scope.subjectName);
		}).error(function() {
			/* Act on the event */
		});
	}

	$scope.deleteSubject = function(subject){
		if(confirm("คุณต้องการลบรายวิชานี้ใช่ไหม")){
			$http.get('php/subject.php?q=delete_subject&section_id='+subject.id)
			.success(function(data){
				console.log(data);
				$scope.goto('teacher-home.php');
			});
		}
	}

	function setTime(time){
		var timeString = time;
		var H = +timeString.substr(0, 2);
		var h = H % 12 || 12;
		var ampm = H < 12 ? " AM" : " PM";
		var hour = h < 10 ? "0"+h : h;
		timeString = hour + timeString.substr(2, 3) + ampm;
		return timeString;
	}

	$scope.setEnroll = function(sid){
		$cookies.put('sid', sid);
		$scope.goto('enroll-subject.php');
	}

	$scope.getStudentEnroll = function(){
		var sid = $cookies.get('sid');
		$http.get('php/studentEnroll.php?sid='+sid).success(function(data){
			console.log(data);
			$scope.enroll = data;
		}).error(function() {
			/* Act on the event */
		});
	}

	$scope.setDataFetchScore = function(sid, subjectId, subjectName){
		$cookies.put('sid', sid);
		$cookies.put('subject_id', subjectId);
		$cookies.put('subject_name', subjectName);
		console.log(sid+" "+subjectName);
		$scope.goto('score.php');
	}

	$scope.getScore = function(){
		$scope.sectionId = $cookies.get('sid');
		$scope.subjectId = $cookies.get('subject_id');
		$scope.subjectName = $cookies.get('subject_name');
		//console.log($scope.sectionId+" "+$scope.subjectName);
		$http.get('php/score.php?q=get_score_by_sid&sid='+$scope.sectionId)
		.success(function(data){
			console.log(data);
			$scope.scores = data;
		}).error(function(data) {
			/* Act on the event */
		});
		$http.get('php/subject.php?q=get_subject&sid='+$scope.sectionId)
		.success(function(data){
			console.log(data);
			$scope.subject = data;
		}).error(function(data) {
			/* Act on the event */
		});
	}

	$scope.seeScore = function(qid){
		$cookies.put('quiz_id', qid);
		$scope.goto('student-score.php');
	}

	$scope.seeAllscore = function(qid){
		$cookies.put('quiz_id,qid');
		$scope.goto('report-score.php');
	}

	$scope.getStudentScore = function(){
		$qid = $cookies.get('quiz_id');
		console.log('qid '+$qid);
		$scope.subjectId = $cookies.get('sid');
		$scope.subjectName = $cookies.get('subject_name');
		$http.get('php/score.php?q=get_total_score&quiz_id='+$qid).success(function(data){
			$scope.totalScore = data;
		});
		$http.get('php/score.php?q=get_student_score&quiz_id='+$qid+'&section_id='+$scope.subjectId)
			.success(function(data){
				console.log(data);
				$scope.students_score = data;
				for (var i = 0; i < $scope.students_score.length; i++) {
					if($scope.students_score[i]['Point'] == null){
						$scope.students_score[i]['Point'] = '-';
					}
					if($scope.students_score[i]['Correct'] == 0){
						$scope.students_score[i]['Correct'] = '-';
					}
					if($scope.students_score[i]['Wrong'] == 0){
						$scope.students_score[i]['Wrong'] = '-';
					}

				}
			})
	}

	$scope.getAllQuizScore = function(){
		var sid = $cookies.get('sid');
		$http.get('php/quiz.php?q=get_all_quiz&sid='+sid).success(function(data){
			console.log(data);
			$scope.quizes = data;
		}).error(function(){});
		$http.get('php/quiz.php?q=get_all_quiz_score&sid='+sid).success(function(data){
			console.log(data);
			$scope.students = data;
		}).error(function(){});
		
	}

	var check;

	$scope.setStudentCheck = function(sid, subject_name){
		$cookies.put('sid', sid);
		$cookies.put('subject_name', subject_name);
		$scope.goto('check-student.php');
	}

	$scope.sendSectoinID = function(){
		var section_id = $cookies.get('sid');
		$http.get('php/send-section-id.php?section_id='+section_id).success(function(data){
				console.log(data);
		}).error(function(data) {
			/* Act on the event */
		});
}


	$scope.getStudentForCheck = function(){
		$scope.SubjectID = $cookies.get('sid');
		$scope.SubjectName = $cookies.get('subject_name');
		if(angular.isDefined(check)){
			$interval.cancel(check);
		 	return;
		}

		check = $interval(function(){
			$http.get('php/check-student.php?section_id='+$scope.SubjectID).success(function(data){
				console.log(data);
				$scope.student_come = 0;
				$scope.check_student = data;
				getStudentCome();
			}).error(function(data) {
				/* Act on the event */
			})}, 1000);
	}

	var getStudentCome = function(){
		var i;
		for(i=0; i<$scope.check_student.length; i++){
			if($scope.check_student[i].isCome){
				 $scope.student_come++;
			}
		}
	}
    
    $scope.subj = "เลือกวิชา"
	$scope.setSubject = function(index){
		$scope.subj = $scope.teacher_subjects[index].Subject_Name;
		console.log($scope.subj);
	}

    $scope.onFilesSelected = function(files) {
     console.log("files - " + files);
     $scope.fileName = files[0].name;
     console.log($scope.fileName);
     if(confirm("คุณต้องการอัพโหลดไฟล์คำถามใช่ไหม")){
     	var quiz_id = $cookies.get('quiz_id');
     	var form = document.getElementById('upload_question');
     	var fd = new FormData(form);
     	fd.append('file', files[0]);
     	$http.post('php/upload-question.php?quiz_id='+quiz_id,fd, {
                transformRequest:angular.identity,
                headers:{'Content-Type':undefined}
        }).success(function(data){
     		console.log(data);
     		alert(data);
     		$window.location.reload();
     	}).error(function() {
     		/* Act on the event */
     	});
     }

	};

	$scope.downloadQuiz = function(quiz_id){
		var sid = $cookies.get('sid');
		console.log(sid);
		$http.post('php/export-question.php?quiz_id='+quiz_id+'&section_id='+sid, {}, {responseType: 'arraybuffer'})
		.success(function(data){
			//console.log(data);
			var file = new Blob([data], {type: 'application/pdf'});
		    var fileURL = URL.createObjectURL(file);
		    $window.open(fileURL);
		}).error(function() {
			/* Act on the event */
		});
	}

	$scope.getStudentCome = function(how){
		if(how == 'day'){
			if($scope.day == null){
				$scope.day = new Date();
			}
			console.log($scope.day);
			var form = document.getElementById('dateForm');
			var fd = new FormData(form);
			$http.post('php/get-student-come.php?q=get_by_day', fd, {
	                transformRequest:angular.identity,
	                headers:{'Content-Type':undefined}
	            }).success(function(data){
	            	console.log(data);
	            	var student_come = 0;
	            	if(data == "No Student"){
	            		$scope.no_student_msg = data;
	            	}
	            	else{
	            		if($scope.subj == "เลือกวิชา"){
	            			alert('กรุณาเลือกวิขา');
	            		}
	            		$scope.no_student_msg = "";
		            	$scope.section_student_come = data;
		            	$scope.statistic_student_come = data;
		            	for (var i = 0; i < $scope.statistic_student_come.length; i++){ 
		            		for(var j=0; j < $scope.statistic_student_come[i].Student.length; j++)
		            			if($scope.statistic_student_come[i].Student[j].isCome) student_come++;
		            		$scope.statistic_student_come[i].student_come = student_come;
		            		student_come = 0;
		            	}
		            }
	            }).error(function() {
	            	/* Act on the event */
	            });
        }
        else if(how == 'all'){
        	$http.post('php/get-student-come.php?q=get_all').success(function(data){
	            	console.log(data);
	            	var student_come = 0;
	            	if(data == "No Student"){
	            		$scope.no_student_msg = data;
	            	}
	            	else{
	            		if($scope.subj == "เลือกวิชา"){
	            			alert('กรุณาเลือกวิขา');
	            		}
	            		$scope.no_student_msg = "";
		            	$scope.section_student_come = data;
		            	for (var i = 0; i < $scope.section_student_come.length; i++){ 
		            		for(var j=0; j < $scope.section_student_come[i].Student.length; j++)
		            			if($scope.section_student_come[i].Student[j].isCome) student_come++;
		            		$scope.section_student_come[i].student_come = student_come;
		            		student_come = 0;
		            	}

		            	var duplicate = false;
		            	$scope.statistic_student_come = new Array();
		            	$scope.statistic_student_come.push($scope.section_student_come[0]);
		            	for (var i = 0; i < $scope.section_student_come.length; i++){ 
		            		for(var j=0; j < $scope.statistic_student_come.length; j++){
		            			if($scope.section_student_come[i].Section_ID == $scope.statistic_student_come[j].Section_ID){
		            				duplicate = true;
		            				break;
		            			}
		            		}
		            		if(!duplicate) {
		            			$scope.statistic_student_come[$scope.statistic_student_come.length] = $scope.section_student_come[i];
		            		}
		            		duplicate = false;
		            	}
		            	console.log($scope.statistic_student_come);
		            }
	            }).error(function() {
	            	/* Act on the event */
	            });
        }
	}

	$scope.setAnswerHistory = function(question_id, question){
		$cookies.put('questionid', question_id);
		$cookies.put('question', question);
		$scope.goto('answer-history.php');
	}

	$scope.getAnswerHistory = function(){
		var sid = $cookies.get('sid');
		var question_id = $cookies.get('questionid');
		$scope.Question = $cookies.get('question');
		$http.get('php/quiz.php?q=get_answer_history&question_id='+question_id+'&sid='+sid).success(function(data){
            	console.log(data);
            	$scope.correct_answer = 0;
            	$scope.wrong_answer = 0;
            	$scope.not_send = 0;
            	if(data == "No Result"){
            		$scope.no_answer_history = data;
            	}
            	else{
            		$scope.no_answer_history = "";
	            	$scope.answer_history = data;
	          		for(var i=0; i<$scope.answer_history.length; i++)
	          			if($scope.answer_history[i].Correct == 1){
	          				$scope.answer_history[i].Correct = true;
	          				$scope.correct_answer++;
	          			}else if($scope.answer_history[i].Correct == 0){
	          				$scope.answer_history[i].Correct = false;
	          				$scope.wrong_answer++;
	          			}else{
	          				$scope.not_send++;
	          			}
	            }
            }).error(function() {
            	/* Act on the event */
            });
	}

	$scope.uploadFile = function(event) {
        var files = event.target.files;
        $scope.file = files[0];
        $timeout(function() {
            var fileReader = new FileReader();
            fileReader.readAsDataURL(files[0]);
            fileReader.onload = function(e) {
                $timeout(function() {
                    var img = document.getElementById('profile-img');
                    img.src = e.target.result;

                });
            }
        });
    };

    $scope.editTeacherProfile = function(){
        if(checkTeacherFormValid()){
            var form = document.getElementById('form_signup');
            var fd = new FormData(form);
            fd.append('image', $scope.file);
            console.log(fd);
            $http.post('php/save-user.php?role=0&q=edit_teacher_profile', fd, {
                    transformRequest:angular.identity,
                    headers:{'Content-Type':undefined}
                }).success(function(data){
                    console.log(data);
                    if(data == 'complete'){
                        window.location.href = 'teacher-profile.php';
                    }
                    else{
                        alert('register incomplete');
                    }
            });
        }
    }

	$scope.editStudentProfile = function(){
        if(checkStudentFormValid()){
            var form = document.getElementById('form_signup');
            var fd = new FormData(form);
            fd.append('image', $scope.file);
            $http.post('php/save-user.php?role=1&q=edit_student_profile', fd, {
                    transformRequest:angular.identity,
                    headers:{'Content-Type':undefined}
                }).success(function(data){
                    console.log(data);
                    if(data == 'complete'){
                        window.location.href = 'student-profile.php';
                    }
                    else{
                        alert('register incomplete');
                    }
            });
        }
    }

    $scope.duplicate_username = "";
    $scope.error_msg = "";

    $scope.check_username = function(){
        $http.get('php/save-user.php?q=check_username&username='+$scope.username)
        .success(function(data){
            console.log(data);
            if(data == "false" && ($scope.user != $scope.username)){
                $scope.duplicate_username = "ชื่อผู้ใช้ซ้ำ";
                return false;
            }else{
                $scope.duplicate_username = "";
                return true;
            }
        }).error(function() {
            /* Act on the event */
        });
    }

    function checkStudentFormValid(){
        if($scope.form_signup.student_id.$valid&&$scope.form_signup.firstname.$valid&&$scope.form_signup.lastname.$valid
            &&$scope.form_signup.email.$valid&&$scope.form_signup.username.$valid&&$scope.form_signup.password.$valid
            &&$scope.error_msg==""&&$scope.duplicate_username==""){ return true;}
        return false;
    }

    function checkTeacherFormValid(){
        if($scope.form_signup.shortname.$valid&&$scope.form_signup.firstname.$valid&&$scope.form_signup.lastname.$valid
            &&$scope.form_signup.email.$valid&&$scope.form_signup.username.$valid&&$scope.form_signup.password.$valid
            &&$scope.error_msg==""&&$scope.duplicate_username==""){ return true;}
        return false;
    }

    $scope.match_password = function(){
        if($scope.password != $scope.repassword){ $scope.error_msg = "password do not match"; return false;}
        else{ $scope.error_msg = ""; return true; }
    }

    var request;
    var student_answer;
    $scope.listStudentAnswer = function(question_index){
		$scope.SubjectID = $cookies.get('sid');
		$scope.isRealtime = $cookies.get('realtime');
		$scope.SubjectName = $cookies.get('subject_name');
		$scope.QuizName = $cookies.get('quiz_name');
		$scope.QuizID = $cookies.get('quiz_id');
		if($scope.date == null){
			$scope.date = new Date();
			console.log($scope.date);
		}
		console.log('Realtime : '+$scope.isRealtime);
		if($scope.isRealtime == 'true'){
			if(angular.isDefined(request)){
				$interval.cancel(request);
			 	return;
			}
			request = $interval(function(){
			$http.get('php/request-answer.php?q=list_answer&date='+$scope.date+'&realtime=true&sid='+$scope.SubjectID).success(function(data){
				//console.log(data);
				$scope.student_answer = data;
				$scope.answer_correct = 0;
				setAnswerFlag();
			}).error(function(data) {
				/* Act on the event */
			})}, 1000);

			}
		// else{
		// 	$scope.QuizID = $scope.QuestionCount[question_index];
		// 	$http.get('php/request-answer.php?quiz_id='+$scope.QuizID+'&realtime=false').success(function(data){
		// 			console.log(data);
		// 			$scope.student_answer = data;
		// 		}).error(function(data) {
		// 			/* Act on the event */
		// 		});
		// }
		
	}

	$scope.getStudentNotCome = function(){
		console.log('dkjfl');
		$http.get('php/check-student.php?section_id='+$scope.SubjectID).success(function(data){
			console.log(data);
			$scope.student_come = 0;
			$scope.check_student = data;

			var i;
			for(i=0; i<$scope.check_student.length; i++){
				if($scope.check_student[i].isCome){
					 $scope.student_come++;
				}
			}
		}).error(function(data) {
			/* Act on the event */
		});
	}

	function setAnswerFlag(){
		if($cookies.get('change-page') == ''){
			console.log('change-page is null');
			$cookies.put('change-page', $cookies.get('clicked-page'));
		}
		else if($cookies.get('change-page') != $cookies.get('clicked-page')){
			console.log('occure evet change page');
			$cookies.put('change-page', $cookies.get('clicked-page'));
			setSendAnswer();
		}
		for(i=0; i<$scope.student_answer.length; i++){
			for(j=0; j<$scope.enroll.Student_Detail.length; j++){
				if($scope.student_answer[i].Student_ID == $scope.enroll.Student_Detail[j].Student_ID){
					$scope.enroll.Student_Detail[j].isAnswer = true;
					$scope.enroll.Student_Detail[j].Answer = $scope.student_answer[i].Answer;
					if($scope.student_answer[i].Correct == 1){
						$scope.enroll.Student_Detail[j].Correct = true;
						$scope.answer_correct++;
					}else{
						$scope.enroll.Student_Detail[j].Correct = false
					}
					break;
				}
			}
		}
		$scope.current_question = parseInt($cookies.get('clicked-page'))+1;

	}

	$scope.listQuestionCount = function(){
		var quiz_id = $cookies.get('quiz_id');
		$http.get('php/quiz.php?q=get_question_count&quiz_id='+quiz_id).success(function(data){
			console.log(data);
			$scope.QuestionCount = data;
			setSendAnswer();
		}).error(function() {
			/* Act on the event */
		});
		
	}

	function setSendAnswer(){
		console.log("send anwer");
		for (var i = 0; i < $scope.enroll.Student_Detail.length; i++) {
			$scope.enroll.Student_Detail[i].isAnswer = false;
			$scope.enroll.Student_Detail[i].Answer = "";
			$scope.enroll.Student_Detail[i].Correct = false;
		}
		console.log($scope.enroll);
	}

}])

myapp.directive('customOnChange', function() {
  return {
    restrict: 'A',
    link: function (scope, element, attrs) {
      var onChangeHandler = scope.$eval(attrs.customOnChange);
      element.bind('change', onChangeHandler);
    }
  };
});