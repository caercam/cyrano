module.exports = {
  mode: 'jit',
  content: [
    '**/*.php',
    '*.php',
    './src/css/*.css',
    './src/js/*.js',
  ],
  theme: {
    extend: {
      backgroundImage: theme => ({
        'footer': "url('../images/footer.png')",
        'header': "url('../images/header.png')",
        'plane': "url('../images/bg-plane.png')",
      }),
      backgroundSize: {
        'auto-full': 'auto 100%',
        'full-auto': '100% auto',
      },
      colors: {
        'cyrano-light': '#efefff',
        'cyrano-dark': '#06090e',
        'cyrano-navy': '#18273c',
        'cyrano-gray': '#aab',
        'cyrano-orange': '#f86037',
        'cyrano-blue': '#5996cd',
        'cyrano-sky': '#accbe6',
        'cyrano-teal': '#6f9d9f',
        'cyrano-green': '#33cd3d',
      },
      fontSize: {
        'xxs': '.625rem',
      },
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
