exports.config = {
    framework: 'jasmine',
    directConnect:true,
    specs:['./education/table.spec.js'],
    //SELENIUM_PROMISE_MANAGER: false,
    jasmineNodeOpts: {
        defaultTimeoutInterval: 2500000},

     capabilities: {
        browserName: 'chrome',
        'chromeOptions':{
            //args :['--headless','--windows-size=1920x1280']
        } 
        
    }
   
}