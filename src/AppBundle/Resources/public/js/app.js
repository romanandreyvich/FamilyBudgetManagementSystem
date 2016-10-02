/**
 * Created by romanbelousov on 25.09.16.
 */
var app = angular.module('mfbsApp', []);

app.config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('[-');
    $interpolateProvider.endSymbol('-]');
});

app.controller('mainController', ['$scope', '$http', function($scope, $http) {
    $scope.page = 'transactions';
    $scope.token = window.salt;
    $scope.userId = window.user_id;
    $scope.transactions =  null;
    $scope.transactionTypes = null;
    $scope.transactionCategories = null;
    $scope.family = null;
    $scope.loading = true;
    $scope.allFamilies = null;

    $scope.from = window.from;
    $scope.to = window.to;
    $scope.type = null;
    $scope.category = null;
    $scope.asset = null;
    $scope.report = null;
    $scope.setFamily = null;
    $scope.setNewFamily = null;
    $scope.newTransactionType = null;
    $scope.newTransactionCategory = null;
    $scope.newTransactionCategoryType = null;

    $http.get(Routing.generate('api_transactions', {'user_id' : $scope.userId, 'access_token' : $scope.token, 'user' : $scope.userId})).success(function(response)
    {
        $scope.transactions = response.result;
    }).error(function(response) {
    });

    $http.get(Routing.generate('api_transaction_category', {'user_id' : $scope.userId, 'access_token' : $scope.token})).success(function(response)
    {
        $scope.transactionCategories = response.result;
    }).error(function(response) {
    });

    $http.get(Routing.generate('api_transaction_type', {'user_id' : $scope.userId, 'access_token' : $scope.token})).success(function(response)
    {
        $scope.transactionTypes = response.result;
    }).error(function(response) {
    });

    $http.get(Routing.generate('api_families', {'user_id' : $scope.userId, 'access_token' : $scope.token, 'user' : $scope.userId})).success(function(response)
    {
        $scope.family = response.result;
        var family = $scope.family.id || false;
        if (!family) {
            $scope.family = false;
            $scope.loading = false;
            return;
        }
        $http.get(Routing.generate('api_get_report', {'user_id' : $scope.userId, 'access_token' : $scope.token, 'family' : family, 'report_type' : '2'})).success(function(response)
        {
            $scope.compare = response.result;
            $scope.loading = false;
        }).error(function(response) {
            $scope.compare = false;
            $scope.loading = false;
        });

    }).error(function(response) {
    });

    $http.get(Routing.generate('api_families', {'user_id' : $scope.userId, 'access_token' : $scope.token})).success(function(response)
    {
        $scope.allFamilies = response.result;
    }).error(function(response) {
    });

    $scope.send = function () {
        if ($scope.category == null || $scope.asset == null) {
            return;
        }
        $scope.loading = true;
        $http.post(Routing.generate('api_create_transaction',
            {
                'user_id' : $scope.userId,
                'access_token' : $scope.token,
                'category' : $scope.category,
                'asset' : $scope.asset
            })
        ).success(function(response)
        {
            $scope.transactions.push(response.result.transaction);
            $scope.type = null;
            $scope.category = null;
            $scope.asset = null;
            $scope.loading = false;

            $http.get(Routing.generate('api_get_report',
                {
                    'user_id' : $scope.userId,
                    'access_token' : $scope.token,
                    'family' : $scope.family.id,
                    'report_type' : '2'
                })
            ).success(function(response)
            {
                $scope.compare = response.result;
                $scope.loading = false;
            }).error(function(response) {
                $scope.compare = false;
                $scope.loading = false;
            });

        }).error(function (response) {
            $scope.loading = false;
            $scope.category = null;
            $scope.asset = null;
        });
    };

    $scope.setPage = function (page) {
        $scope.page = page;
    };

    $scope.setFamilyButton = function () {
        if ($scope.setFamily == null) {
            return;
        }
        $scope.loading = true;
        $http.put(Routing.generate('api_set_family',
            {
                'user_id' : $scope.userId,
                'access_token' : $scope.token,
                'family_id' : $scope.setFamily,
                'current_user' : $scope.userId
            })
        ).success(function(response)
        {
            $scope.family = response.result.family;
            $scope.loading = false;
            $scope.setFamily = null;

            $http.get(Routing.generate('api_get_report',
                {
                    'user_id' : $scope.userId,
                    'access_token' : $scope.token,
                    'family' : $scope.family.id,
                    'report_type' : '2'
                })
            ).success(function(response)
            {
                $scope.compare = response.result;
                $scope.loading = false;
            }).error(function(response) {
                $scope.loading = false;
                $scope.loading = false;
            });
        }).error(function(response) {
        });
    };

    $scope.setNewFamilyButton = function () {
        if ($scope.setNewFamily == null) {
            return;
        }
        $scope.loading = true;
        $http.post(Routing.generate('api_create_family', {'user_id' : $scope.userId, 'access_token' : $scope.token, 'lastname' : $scope.setNewFamily})).success(function(response)
        {
            $http.put(Routing.generate('api_set_family',
                {
                    'user_id' : $scope.userId,
                    'access_token' : $scope.token,
                    'family_id' : response.family.id,
                    'current_user' : $scope.userId
                })
            ).success(function(response)
            {
                window.location.reload();
            }).error(function(response) {
                $scope.loading = false;
            });

            $scope.setNewFamily = null;
            $scope.loading = false;
        }).error(function(response) {
            $scope.setNewFamily = null;
            $scope.loading = false;
        });
    };

    $scope.newTransactionTypeButton = function () {
        if ($scope.newTransactionType == null) {
            return;
        }
        $scope.loading = true;
        $http.post(Routing.generate('api_create_transaction_type', {'user_id' : $scope.userId, 'access_token' : $scope.token, 'name' : $scope.newTransactionType})).success(function(response)
        {
            $scope.transactionTypes.push(response.result.transactionType);
            $scope.newTransactionType = null;
            $scope.loading = false;
        }).error(function(response) {
            $scope.newTransactionType = null;
            $scope.loading = false;
        });
    };

    $scope.newTransactionCategoryButton = function () {
        if ($scope.newTransactionCategory == null || $scope.newTransactionCategoryType == null) {
            return;
        }
        $scope.loading = true;
        $http.post(Routing.generate('api_create_transaction_category', {'user_id' : $scope.userId, 'access_token' : $scope.token, 'name' : $scope.newTransactionCategory, 'type' : $scope.newTransactionCategoryType})).success(function(response)
        {
            $scope.transactionCategories.push(response.result.transactionCategory);
            $scope.newTransactionCategory = null;
            $scope.newTransactionCategoryType = null;
            $scope.loading = false;
        }).error(function(response) {
            $scope.newTransactionCategory = null;
            $scope.loading = false;
        });
    };

    $scope.draw = function (index, compare, username) {
        var userdata = [];
        userdata.push(['Task', 'Доли расхода/прихода']);

        for(var key in compare) {
            userdata.push([key + " - " + compare[key] + " руб.", compare[key]]);
        }

        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable(userdata);

            var options = {
                title: '',
                pieHole: 0.2
            };

            var chart = new google.visualization.PieChart(document.getElementById('index'+index));
            chart.draw(data, options);
        }
    };

    var indexedCategory = [];
    $scope.categoryToFilter = function(transactions) {
        indexedCategory = [];
        return transactions;
    };
    
    $scope.filterCategory = function(transaction) {
        var categoryIsNew = indexedCategory.indexOf(transaction.category) == -1;
        if (categoryIsNew) {
            indexedCategory.push(transaction.category);
        }
        return categoryIsNew;
    };
}]);

app.filter('groupby', function(){
    return function(items, group){
        return items.filter(function(element, index, array) {
            return element;
        });
    }
});