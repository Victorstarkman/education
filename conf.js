exports.config = {
    framework: 'jasmine',
    directConnect:true,
    specs:['./education/table.spec.js'],
    jasmineNodeOpts: {
        defaultTimeoutInterval: 2500000}

   /*  capabilities: {
        browserName: 'firefox',
        'moz:firefoxOptions':{
            args :['--headless']
        } 
        
    }*/
   
}