module.exports = (grunt) ->
  @initConfig
    pkg: @file.readJSON('package.json')
    watch:
      files: ['**/**.coffee', '**/*.scss']
      tasks: ['default']
    coffee:
      compile:
        options:
          bare: true
          sourceMap: true
        files:
          'js/display.min.js': 'js/src/display.coffee'
          'js/admin.min.js': 'js/src/admin.coffee'
    compass:
      dist:
        options:
          config: 'config.rb'
    jshint:
      files: [
        'js/display.min.js'
        'js/admin.min.js'
      ]
      options:
        globals:
          jQuery: true
          console: true
          module: true
          document: true
    csslint:
      src: ['css/*.css']
    cssmin:
      compress:
        options:
          banner: "/* Don't even attempt to edit this file */"
          report: 'min'
        files:
          'css/admin.min.css': ['css/admin.css']
          'css/display.min.css': ['css/display.css']

  @loadNpmTasks 'grunt-contrib-coffee'
  @loadNpmTasks 'grunt-contrib-compass'
  @loadNpmTasks 'grunt-contrib-jshint'
  @loadNpmTasks 'grunt-contrib-csslint'
  @loadNpmTasks 'grunt-contrib-cssmin'
  @loadNpmTasks 'grunt-contrib-watch'

  @registerTask 'default', ['coffee', 'jshint', 'compass', 'csslint']
  @registerTask 'package', ['default', 'cssmin']
  @registerTask 'phpscan', 'Compare results of vip-scanner with known issues', ->
    done = @async()

    # Ensure strings use the same HTML characters
    unescape_html = (str) ->
      str.replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace /&gt;/g, '>'

    # Known issues
    known_issues = grunt.file.readJSON('known-issues.json')
    known_issues_string = JSON.stringify(known_issues)
    known_issues_string = unescape_html(known_issues_string)

    # Current issues
    current_issues = grunt.file.read('vipscan.json')
    start = current_issues.indexOf('[{')
    end = current_issues.lastIndexOf('}]')
    current_issues_string = current_issues.slice(start, end) + '}]'
    current_issues_string = unescape_html(current_issues_string)
    current_issues_json = JSON.parse(current_issues_string)

    # New issues
    new_issues = []
    i = 0
    while i < current_issues_json.length
      issue = current_issues_json[i]
      issue_string = JSON.stringify(issue)
      if known_issues_string.indexOf(issue_string) < 0
        new_issues.push(issue)
      i++

    # Display issues information
    grunt.log.writeln('--- VIP Scanner Results ---')
    grunt.log.writeln(known_issues.length + ' known issues.')
    grunt.log.writeln(current_issues_json.length + ' current issues.')
    grunt.log.writeln(new_issues.length + ' new issues:')
    grunt.log.writeln '------------------'
    i = 0
    while i < new_issues.length
      obj = new_issues[i]

      for key,value of obj
        if value != ''
          if Array.isArray(value)
            value = value.join(' ')
            grunt.log.writeln(key + ': ' + value)
          else if typeof value == 'object'
            grunt.log.writeln(key + ':')
            for key2,value2 of value
              grunt.log.writeln('>> Line ' + key2 + ': ' + value2)
          else
            grunt.log.writeln(key + ': ' + value)

      grunt.log.writeln '------------------'
      i++

    grunt.log.writeln('All issues in JSON: ' + JSON.stringify(new_issues))

    return

  @event.on 'watch', (action, filepath) =>
    @log.writeln('#{filepath} has #{action}')
