# Test path
TESTPATH = 'test'
COVERAGE = File.join(TESTPATH, 'coverage')

# We don't want sh commands being verbose
RakeFileUtils.verbose_flag = false

task :default do
  puts 'Nothing by default'
end

desc 'Clean up code coverage'
task :clean do |t|
  [
    File.join(COVERAGE,  '*'),
  ].each do |glob|
  	print 'Deleting all but .gitignore in '+glob+'...'
    Dir.glob(glob).reject {|file| file == '.gitignore'} .each {|file| rm_rf file}
    puts ' done.'
  end
end