/**
 * INSPINIA - Responsive Admin Theme
 *
 */
(function () {
    angular.module('inspinia', [
        'ui.router',                    // Routing
        'oc.lazyLoad',                  // ocLazyLoad
        'ui.bootstrap',                 // Ui Bootstrap
        'pascalprecht.translate',       // Angular Translate
        'ngIdle',                       // Idle timer
        'ngSanitize'                    // ngSanitize
    ])
    .filter('filterByRegion', function () {
    return function (secondSelect, firstSelect) {
        var filtered = [];
        if (firstSelect === null) {
            return filtered;
        }
        angular.forEach(secondSelect, function (s2) {
            if (s2.region_id == firstSelect) {
                filtered.push(s2);
            }
        });
        return filtered;
    };
})
.filter('sumByStatus', function () {
    return function (items, status) {
        var sum = 0;
        if (status === null) {
            return sum;
        }
        angular.forEach(items, function (item) {
            if (item.status == status) {
                 sum = sum + item.amount;
               
                console.log("current amount: "+ item.amount);
             
            }
        });
         console.log("sum: "+ sum);
        return sum;
    };
})
.filter('sumByKey', function() {
        return function(data, key) {
            if (typeof(data) === 'undefined' || typeof(key) === 'undefined') {
                return 0;
            }

            var sum = 0;
            for (var i = data.length - 1; i >= 0; i--) {
                sum += parseInt(data[i][key]);
            }

            return sum;
        };
    })
.constant('API_URL', 'http://localhost:8000/api/v1/');
})();

// Other libraries are loaded dynamically in the config.js file using the library ocLazyLoad