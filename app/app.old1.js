var app = angular.module('agendaApp', ['ngRoute']);


// FASE DE TESTES - DIRECTIVE AINDA NAO ESTA SENDO UTILIZADA NO CODIGO HTML
// ainda nao funcionando
app.directive('modalDialog', function()
{
	return {
		restrict: 'E',
		scope: {
			show: '='
		},
		replace: true,
		transclude: true,
		link: function(scope, element, attrs)
		{
			scope.dialogStyle = {};
			if (attrs.width) {
				scope.dialogStyle.width = attrs.width;
			}
			if (attrs.height) {
				scope.dialogStyle.height = attrs.height;
			}
			scope.hideModal = function()
			{
				scope.show = false;
			};
		},
		template: 'partials/modal_dialog.html'
	};
});


app.factory('headerSetup', function()
{
	function setupButtons(homeText, homeLink, homeStyle, newText, newLink, newStyle, other1Text, other1Link, other1Style, other2Text, other2Link, other2Style)
	{
		return {
			btnHomeText: homeText,
			btnHomeLink: homeLink,
			btnHomeStyle: !(homeStyle == null || homeStyle == undefined) ? homeStyle : 'btn-lg btn-primary',
			btnNewText: newText,
			btnNewLink: newLink,
			btnNewStyle: !(newStyle == null || newStyle == undefined) ? newStyle : 'btn-lg btn-success',
			btnOther1Text: other1Text,
			btnOther1Link: other1Link,
			btnOther1Style: !(other1Style == null || other1Style == undefined) ? other1Style : 'btn-lg btn-danger',
			btnOther2Text: other2Text,
			btnOther2Link: other2Link,
			btnOther2Style: !(other2Style == null || other2Style == undefined) ? other2Style : 'btn-lg btn-danger'
		};
	}

	var obj = {};
	obj.listContatos = function()
	{
		return setupButtons(
			'Contatos', 'list_contatos', null,
			'Novo Contato', 'edit_contato', null,
			'Telefones', 'list_telefones', null,
			'Emails', 'list_emails', null
		);
	};
	obj.listTelefones = function()
	{
		return setupButtons(
			'Telefones', 'list_telefones', null,
			'Novo Telefone', 'edit_telefone', null,
			'Contatos', 'list_contatos', null
		);
	};
	obj.listEmails = function()
	{
		return setupButtons(
			'Emails', 'list_emails', null,
			'Novo Email', 'edit_email', null,
			'Contatos', 'list_contatos', null,
			'Trocar exibição do Nome', ''
		);
	};

	return obj;
});


app.factory('services', ['$http', function($http)
{
	var serviceBasePath = 'services/';
	var obj = {};

	// contato:
	obj.getContatos = function()
	{
		return $http.get(serviceBasePath + 'contatos');
	};
	obj.getContato = function(id)
	{
		return $http.get(serviceBasePath + 'contato?id=' + id);
	};
	obj.insertContato = function(contato)
	{
		return $http.post(serviceBasePath + 'insertContato', contato).then(function(results)
		{
			return results;
		});
	};
	obj.updateContato = function(id, contato)
	{
		return $http.post(serviceBasePath + 'updateContato', {id: id, contato: contato}).then(function(status)
		{
			return status.data;
		});
	};
	obj.deleteContato = function(id)
	{
		return $http.delete(serviceBasePath + 'deleteContato?id=' + id).then(function(status)
		{
			return status.data;
		});
	};

	// telefone:
	obj.getTelefones = function()
	{
		return $http.get(serviceBasePath + 'telefones');
	};
	obj.getTelefone = function(id)
	{
		return $http.get(serviceBasePath + 'telefone?id=' + id);
	};
	obj.insertTelefone = function(telefone)
	{
		return $http.post(serviceBasePath + 'insertTelefone', telefone).then(function(results)
		{
			return results;
		});
	};
	obj.updateTelefone = function(id, telefone)
	{
		return $http.post(serviceBasePath + 'updateTelefone', {id: id, telefone: telefone}).then(function(status)
		{
			return status.data;
		});
	};
	obj.deleteTelefone = function(id)
	{
		return $http.delete(serviceBasePath + 'deleteTelefone?id=' + id).then(function(status)
		{
			return status.data;
		});
	};

	// email:
	obj.getEmails = function()
	{
		return $http.get(serviceBasePath + 'emails');
	};
	obj.getEmail = function(id)
	{
		return $http.get(serviceBasePath + 'email?id=' + id);
	};
	obj.insertEmail = function(email)
	{
		return $http.post(serviceBasePath + 'insertEmail', email).then(function(results)
		{
			return results;
		});
	};
	obj.updateEmail = function(id, email)
	{
		return $http.post(serviceBasePath + 'updateEmail', {id: id, email: email}).then(function(status)
		{
			return status.data;
		});
	};
	obj.deleteEmail = function(id)
	{
		return $http.delete(serviceBasePath + 'deleteEmail?id=' + id).then(function(status)
		{
			return status.data;
		});
	};

	return obj;
}]);


/**
 * Formata um numero inteiro, adicionando zeros ao seu inicio ate que este atinga o tamanho solicitado.
 */
app.filter('numberFixedLengthFormatter', function()
{
	/**
	 * @param number O numero a ser formatado.
	 * @param numberLength O tamanho de caracteres que o numero formatado deve ter.
	 * @returns O numero formatado.
	 */
	return function(number, numberLength)
	{
		var num = parseInt(number, 10);
		numberLength = parseInt(numberLength, 10);
		if (isNaN(num) || isNaN(numberLength)) {
			return number;
		}
		num = '' + num;
		while (num.length < numberLength) {
			num = '0' + num;
		}
		return num;
	}
});


// list_contatos:
app.controller('listContatosCtrl', function($scope, $rootScope, services, headerSetup)
{
	$scope.headerContent = headerSetup.listContatos();
	services.getContatos().then(function(data)
	{
		$scope.contatos = data.data;
	});
});

// edit_contato:
app.controller('editContatoCtrl', function($scope, $rootScope, $location, $routeParams, services, contato)
{
	// verifica se entrou o parametro id:
	var id = ($routeParams.id) ? parseInt($routeParams.id) : 0;
	// seta o titulo da pagina e o texto do botao submit do form:
	$rootScope.title = (id > 0) ? 'Editar Contato' : 'Novo Contato';
	$scope.actionButtonText = (id > 0) ? 'Salvar' : 'Adicionar';

	// faz uma copia do contato original:
	var original = contato.data;
	original._id = id;
	$scope.contato = angular.copy(original);
	$scope.contato._id = id;

	/**
	 * Verifica se os dados do contato original sao iguais ao do contato do escopo do angular que esta sendo editado.
	 * @returns {boolean}
	 */
	$scope.isClean = function()
	{
		return angular.equals(original, $scope.contato);
	};

	$scope.saveContato = function(contato)
	{
		if (id > 0) {
			services.updateContato(id, contato);
		} else {
			services.insertContato(contato);
		}
		$location.path('/list_contatos');
	};

	$scope.deleteContato = function(contato)
	{
		var confirmOperation = confirm('Tem certeza que deseja remover o Contato de ID: ' + $scope.contato._id);
		if (confirmOperation == true) {
			services.deleteContato(contato.id);
		}
		$location.path('/list_contatos');
	};
});


// list_telefones:
app.controller('listTelefonesCtrl', function($scope, $rootScope, services, headerSetup)
{
	$scope.headerContent = headerSetup.listTelefones();

	$scope.modalShown = false;
	$scope.toggleModal = function()
	{
		$scope.modalShown = !$scope.modalShown;
	};

	services.getTelefones().then(function(data)
	{
		$scope.telefones = data.data;
	});
});

// edit_telefone:
app.controller('editTelefoneCtrl', function($scope, $rootScope, $location, $routeParams, services, telefone)
{
	// verifica se entrou o parametro id:
	var id = ($routeParams.id) ? parseInt($routeParams.id) : 0;
	// seta o titulo da pagina e o texto do botao submit do form:
	$rootScope.title = (id > 0) ? 'Editar Telefone' : 'Novo Telefone';
	$scope.actionButtonText = (id > 0) ? 'Salvar' : 'Adicionar';

	// faz uma copia do telefone original:
	var original = telefone.data;
	original._id = id;
	$scope.telefone = angular.copy(original);
	$scope.telefone._id = id;

	/**
	 * Verifica se os dados do telefone original sao iguais ao do telefone do escopo do angular que esta sendo editado.
	 * @returns {boolean}
	 */
	$scope.isClean = function()
	{
		return angular.equals(original, $scope.telefone);
	};

	$scope.saveTelefone = function(telefone)
	{
		if (id > 0) {
			services.updateTelefone(id, telefone);
		} else {
			services.insertTelefone(telefone);
		}
		$location.path('/list_telefones');
	};

	$scope.deleteTelefone = function(telefone)
	{
		var confirmOperation = confirm('Tem certeza que deseja remover o Telefone de ID: ' + $scope.telefone._id);
		if (confirmOperation == true) {
			services.deleteTelefone(telefone.id);
		}
		$location.path('/list_telefones');
	};
});


// list_email:
app.controller('listEmailsCtrl', function($scope, $rootScope, services, headerSetup)
{
	$scope.headerContent = headerSetup.listEmails();
	services.getEmails().then(function(data)
	{
		$scope.emails = data.data;
	});

	$scope.contatoNomeReverseMode = false;
	$scope.getContatoFullName = function(email)
	{
		if ($scope.contatoNomeReverseMode) {
			return email.contato.sobrenome + ', ' + email.contato.nome;
		} else {
			return email.contato.nome + ' ' + email.contato.sobrenome;
		}
	};

	$scope.setContatoNomeMode = function()
	{
		$scope.contatoNomeReverseMode = !$scope.contatoNomeReverseMode;
	};
});

// edit_email:
app.controller('editEmailCtrl', function($scope, $rootScope, $location, $routeParams, services, email)
{
	// verifica se entrou o parametro id:
	var id = ($routeParams.id) ? parseInt($routeParams.id) : 0;
	// seta o titulo da pagina e o texto do botao submit do form:
	$rootScope.title = (id > 0) ? 'Editar Contato' : 'Novo Contato';
	$scope.actionButtonText = (id > 0) ? 'Salvar' : 'Adicionar';

	// faz uma copia do email original:
	var original = email.data;
	original._id = id;
	$scope.email = angular.copy(original);
	$scope.email._id = id;

	/**
	 * Verifica se os dados do email original sao iguais ao do email do escopo do angular que esta sendo editado.
	 * @returns {boolean}
	 */
	$scope.isClean = function()
	{
		return angular.equals(original, $scope.email);
	};

	$scope.saveContato = function(email)
	{
		if (id > 0) {
			services.updateEmail(id, email);
		} else {
			services.insertEmail(email);
		}
		$location.path('/list_emails');
	};

	$scope.deleteEmail = function(email)
	{
		var confirmOperation = confirm('Tem certeza que deseja remover o Email de ID: ' + $scope.email._id);
		if (confirmOperation == true) {
			services.deleteEmail(email.id);
		}
		$location.path('/list_emails');
	};
});


app.config(['$routeProvider',
	function($routeProvider)
	{
		$routeProvider.
			when('/', {
				title: 'Contatos',
				templateUrl: 'partials/list_contatos.html',
				controller: 'listContatosCtrl'
			})
			.when('/list_telefones', {
				title: 'Telefones',
				templateUrl: 'partials/list_telefones.html',
				controller: 'listTelefonesCtrl'
			})
			.when('/list_emails', {
				title: 'Emails',
				templateUrl: 'partials/list_emails.html',
				controller: 'listEmailsCtrl'
			})
			.when('/edit_contato/:id', {
				title: 'Editar Contatos',
				templateUrl: 'partials/edit_contato.html',
				controller: 'editContatoCtrl',
				resolve: {
					contato: function(services, $route)
					{
						var id = $route.current.params.id;
						return services.getContato(id);
					}
				}
			})
			.when('/edit_telefone/:id', {
				title: 'Editar Telefones',
				templateUrl: 'partials/edit_telefone.html',
				controller: 'editTelefoneCtrl',
				resolve: {
					contato: function(services, $route)
					{
						var id = $route.current.params.id;
						return services.getTelefone(id);
					}
				}
			})
			.when('/edit_email/:id', {
				title: 'Editar Emails',
				templateUrl: 'partials/edit_email.html',
				controller: 'editEmailCtrl',
				resolve: {
					contato: function(services, $route)
					{
						var id = $route.current.params.id;
						return services.getEmail(id);
					}
				}
			})
			.otherwise({
				redirectTo: '/'
			});
	}
]);


app.run(['$location', '$rootScope', function($location, $rootScope)
{
	$rootScope.$on('$routeChangeSuccess', function(event, current, previous)
	{
		$rootScope.title = current.$$route.title;
	});
}]);
