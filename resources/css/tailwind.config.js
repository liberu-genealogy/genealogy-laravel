module.exports = {
  content: ['./resources/**/*.blade.php', './resources/**/*.js', './resources/**/*.vue'],
  theme: {
    extend: {
      colors: {
        'blue': {
          '500': '#3b82f6',
          '600': '#2563eb',
          '700': '#1d4ed8'
        },
        'gray': {
          '100': '#f3f4f6',
          '200': '#e5e7eb',
          '700': '#374151',
          '800': '#1f2937'
        }
      },
      fontSize: {
        'sm': ['14px', '20px'],
        'base': ['16px', '24px'],
        'lg': ['20px', '28px'],
        'xl': ['24px', '32px']
      }
    }
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography')
  ]
}
