myapp.controller('registerCtrl', ['$scope', '$timeout', '$http', '$cookies', function($scope, $timeout, $http, $cookies) {

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

    $scope.teacher_register = function(){
        if(checkTeacherFormValid()){
            var form = document.getElementById('form_signup');
            var fd = new FormData(form);
            fd.append('image', $scope.file);
            console.log(fd);
            $http.post('php/save-user.php?role=0&q=teacher_register', fd, {
                    transformRequest:angular.identity,
                    headers:{'Content-Type':undefined}
                }).success(function(data){
                    console.log(data);
                    if(data == 'complete'){
                        window.location.href = 'teacher-home.php';
                    }
                    else{
                        alert('register incomplete');
                    }
            });
        }
    }

    $scope.student_register = function(){
        if(checkStudentFormValid()){
            var form = document.getElementById('form_signup');
            var fd = new FormData(form);
            fd.append('image', $scope.file);
            $http.post('php/save-user.php?role=1&q=student_register', fd, {
                    transformRequest:angular.identity,
                    headers:{'Content-Type':undefined}
                }).success(function(data){
                    console.log(data);
                    if(data == 'complete'){
                        window.location.href = 'student-home.php';
                    }
                    else{
                        alert('register incomplete');
                    }
            });
        }
    }

    $scope.check_username = function(){
        $http.get('php/save-user.php?q=check_username&username='+$scope.username)
        .success(function(data){
            console.log(data);
            if(data == "false"){
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
        if($scope.form_signup.id.$valid&&$scope.form_signup.firstname.$valid&&$scope.form_signup.lastname.$valid
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

    $scope.login = function(){
        var form = document.getElementById('form-signin');
        var fd = new FormData(form);
        $http.post('php/login.php', fd, {
                transformRequest:angular.identity,
                headers:{'Content-Type':undefined}
            }).success(function(data){
                console.log(data);
                if(data == '1'){
                    window.location.href = 'student-home.php';
                }
                else if(data == '0'){
                    window.location.href = 'teacher-home.php';
                }
                else{
                    $scope.error_msg = "username or password incorrect";
                }
               
        });
    }

}])

myapp.directive('customOnChange', function() {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            var onChangeHandler = scope.$eval(attrs.customOnChange);
            element.bind('change', onChangeHandler);
        }
    };
});
