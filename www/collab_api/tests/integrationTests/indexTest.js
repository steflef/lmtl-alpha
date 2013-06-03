//var casper = require('casper').create();

casper.start('http://localhost/lmtl_alpha/www/collab_api', function(response) {
    this.echo(
        response.headers.get('Date') + " ===============================",
        'INFO_BAR'
    );

    if (this.exists('#logout')) {
        this.click('#logout');
        this.echo('found #logout', 'INFO_BAR');
    }
});

casper.thenOpen('http://localhost/lmtl_alpha/www/collab_api', function(){

    this.test.assertEquals(
        this.getCurrentUrl(),
        'http://localhost/lmtl_alpha/www/collab_api/login',
        'Redirect url is the one expected'
    );

    this.test.assertHttpStatus(301, 'Redirect to Login');
});

// WRONG EMAIL
casper.then(function() {

    this.fill('form#login-form',
        {
            email: 'wrong@admin.com',
            password: 'admin'
        }, true);
});

casper.then(function() {

    this.test.assertHttpStatus(301, 'Return to Login');

    this.test.assertEquals(
        this.fetchText({type:'xpath', path:'//*[@id="login-form"]/p[1]/span'}),
        'Courriel introuvable',
        'Error Msg'
    );
});

// WRONG PASSWORD
casper.then(function() {

    this.fill('form#login-form',
        {
            email: 'admin@admin.com',
            password: 'admin_'
        }, true);
});

casper.then(function() {

    this.test.assertHttpStatus(301, 'Return to Login');

    this.test.assertEquals(
        this.fetchText({type:'xpath', path:'//*[@id="login-form"]/p[2]/span'}),
        'Le mot de passe ne correspond pas',
        'Password Msg'
    );
});

casper.then(function() {

    this.fill('form#login-form',
        {
            email: 'admin@admin.com',
            password: 'admin'
        }, true);

});

casper.then(function() {
    this.test.assertEquals(
        this.getCurrentUrl(),
        'http://localhost/lmtl_alpha/www/collab_api/#!/',
        'TB url is the one expected'
    );
});

//casper.thenOpen('http://www.iproperty.com.my/useracc/Login.aspx', function(){
//    this.test.assertExists('form#frm', 'Login form is found');
//    this.fill('form[id="frm"]', {
//        txtEmail: 'andy@andykelk.net',
//        txtPassword: 'xxxxx'
//    }, true);
//});
//
//casper.then(function() {
//    this.test.assertTitle('My Account', 'Login title is ok');
//    this.test.assertEquals(
//        this.evaluate(function() {
//                return __utils__.findOne('span.sidebar-text5').innerText}
//        ),
//        'Andy',
//        'Account name is retrieved ok'
//    );
//});

casper.run(function() {
    this.test.done(7);
    //this.test.renderResults(true);
});