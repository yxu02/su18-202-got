const Authentication = require('./controllers/authentication');
const passportService = require('./services/passport');
const passport = require('passport');

//specify to use jwt strategy, and don't create session (session is for cookie based authentication)
//passport default authentication is session: true
const requireAuth = passport.authenticate('jwt', { session: false });
const requireSignin = passport.authenticate('local', { session: false });

module.exports = function(app) {
  app.get('/', requireAuth, function(req, res) {
    res.send(req.user.id);
  });
  app.post('/signin', requireSignin, Authentication.signin);
  app.post('/signup', Authentication.signup);
}
