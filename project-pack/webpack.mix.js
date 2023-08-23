const mix = require('laravel-mix');

mix.webpackConfig({
  stats: {
    children: true,
  }
});

/**
 * General
 */
mix
  .js('./src/main.js', 'dist/project-pack.main.bundle.js') 
  .js('./src/admin.js', 'dist/project-pack.admin.bundle.js')
  .sass('./src/scss/main.scss', 'css/project-pack.main.bundle.css')
  .sass('./src/scss/admin.scss', 'css/project-pack.admin.bundle.css')
  .options({
    processCssUrls: false
  })
  .setPublicPath('dist');

/**
 * Salesforce
 */
mix
  .js('./src/js/salesforce/admin/index.js', 'dist/salesforce.admin.bundle.js')
  .react()
  .options({
    processCssUrls: false
  })
  .setPublicPath('dist');