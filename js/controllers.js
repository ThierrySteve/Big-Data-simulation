var salmApp = angular.module('salmApp', []);

salmApp.controller('ListCtrl', function ($scope) {
  $scope.phones = [
    {'name': 'TEST PBOT 1',
     'snippet': 'Ok Let\'s coding !'}
  ];
});