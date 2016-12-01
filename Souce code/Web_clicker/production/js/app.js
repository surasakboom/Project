var myapp =angular.module('myapp', ['ngCookies']);


myapp.directive('stringToNumber', function() {
  return {
    require: 'ngModel',
    link: function(scope, element, attrs, ngModel) {
      ngModel.$parsers.push(function(value) {
        return '' + value;
      });
      ngModel.$formatters.push(function(value) {
        return parseFloat(value, 10);
      });
    }
  };
});

myapp.directive('onFileChange', function() {
  return {
    restrict: 'A',
    link: function (scope, element, attrs) {
      var onChangeHandler = scope.$eval(attrs.onFileChange);

      element.bind('change', function() {
        scope.$apply(function() {
          var files = element[0].files;
          if (files) {
            onChangeHandler(files);
          }
        });
      });

    }
  };
});