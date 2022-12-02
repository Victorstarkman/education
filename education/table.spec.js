var fs = require('fs');
describe('Handling table education',()=>{
    beforeAll(async()=>{
        browser.waitForAngularEnabled(false);
        await browser.get("https://login.abc.gob.ar/nidp/idff/sso?id=ABC-Form&sid=1&option=credential&sid=1&target=https://menu.abc.gob.ar/");
        await browser.manage().window().maximize();
    })
    it('input user and password',async()=>{
        
        await browser.waitForAngularEnabled(false);
        element(by.id("Ecom_User_ID")).sendKeys('27240122524');
        
        element(by.id("Ecom_Password")).sendKeys('24012252');
       

       element(by.xpath('//*[@id="modallog"]/div/div/div[2]/div[2]/a')).click();

       browser.sleep(5000); 
       element(by.linkText('Mis Licencias')).click();
       browser.sleep(5000)

    })
    it('enter in the ion area',async()=>{
       
        var windowHandles =    browser.getAllWindowHandles()
        windowHandles.then(function(handles){
            var parentWindow = handles[0];
            var tabbedWindow = handles[1];
       
            browser.waitForAngularEnabled(true);
            browser.switchTo().window(tabbedWindow);

        element(by.xpath('/html/body/ion-app/ng-component/ion-nav/page-home/ion-content/div[2]/div[2]/ion-grid/ion-row/ion-col/ion-list/ion-item')).click();
        browser.sleep(10000);
        })    
    })

    it('scroll pages',async()=>{
      let date = new Date().toLocaleDateString();
      console.log(date);
     
      var j =0;
      //actualizador-si contador no es indefinido o es mayor que 0 llega hasta la pagina donde se cayo sino empeza de 0 
      /* var contador= window.localStorage.get(contador);
      if (contador>0 || typeof(contador)!=undefined){
        for(let k = 0; k< contador;k++){
          j= contador;
          var pager= element(by.xpath('//body[1]/ion-app[1]/ng-component[1]/ion-nav[1]/page-listado-solicitudes-prestadora[1]/ion-content[1]/div[2]/ion-grid[1]/ion-row[2]/ion-col[1]/listado-solicitudes[1]/div[1]/div[1]/div[3]/button[1]/span[1]')).click();
        }
        
      }else{
        localStorage.setItem('contador',j+1);
      } */
      let datetodir = date.split('/').join('');
      var path ='./jsonfiles/'
      var dir = path+datetodir;
      console.log(dir);
      if (!fs.existsSync(dir)){
        fs.mkdirSync(dir);
      }
      do{
       
        await browser.waitForAngularEnabled(true);
        let table= $("table tbody");
        let rows= table.$$("tr");
        
        let count = await rows.count();
        console.log(count);
        //expect(count).toBe(20);
        var arrayTotal=[];
        var jsonObj={};
        for(let i=0; i<count;i++){
          let firstTD=rows.get(i).$$("td");
          let ID = firstTD.get(0);
          //console.log(await ID.getText()); 
          jsonObj = {...jsonObj,ID: await ID.getText()} 
          let name = firstTD.get(1);
          // console.log(await name.getText());
          jsonObj = {...jsonObj,name: await name.getText()} 
          let CUIL = firstTD.get(2);
          //console.log(await CUIL.getText());
          jsonObj = {...jsonObj,CUIL: await CUIL.getText()} 
          let creationDate = firstTD.get(3);
          var createdday=  await creationDate.getText();
          //console.log(await creationDate.getText());
          jsonObj = {...jsonObj,creationDate: await creationDate.getText()} 
          let acceptedDays= firstTD.get(5);
          //console.log(await acceptedDays.getText());
          jsonObj = {...jsonObj,acceptedDays: await  acceptedDays.getText()} 
          let state = firstTD.get(6);
          //console.log(await state.getText());
          jsonObj = {...jsonObj,state: await state.getText()} 
          let assigned = firstTD.get(7);
            //console.log(await assigned.getText());
          jsonObj = {...jsonObj,assigned: await assigned.getText()} 
          assigned.click();
          let diagnostic= element(by.xpath('//*[@id="main"]/ion-list/ion-item[1]/div[1]/div/ion-label/ion-grid/ion-row[6]/ion-col/span'));
            //console.log(await diagnostic.getText());
          if(await diagnostic.isPresent()){
              jsonObj = {...jsonObj,diagnostic: await diagnostic.getText()} 
            }
            let address = element(by.xpath('//*[@id="main"]/ion-list/ion-item[3]/div[1]/div/ion-label/ion-grid/ion-row[2]/ion-col/span[1]'));
          if (await address.isPresent()){
              // console.log(await address.getText());
              jsonObj = {...jsonObj,address: await address.getText()} 
            }
          let county = element(by.xpath('//*[@id="main"]/ion-list/ion-item[3]/div[1]/div/ion-label/ion-grid/ion-row[2]/ion-col/span[2]'));
          if(await county.isPresent()){
              jsonObj = {...jsonObj,county: await county.getText()} 
            }
            
          let readec= element(by.xpath('//*[@id="main"]/ion-list/ion-item[5]/div[1]/div/ion-label/ion-grid/ion-row[1]/ion-col/span'));
          if(await readec.isPresent()){
              //console.log(await readec.getText());
              jsonObj = {...jsonObj,readec: await readec.getText()} 
            }
          let prof= element(by.xpath('//*[@id="main"]/ion-list/ion-item[5]/div[1]/div/ion-label/ion-grid/ion-row[2]/ion-col'));
          if (await prof.isPresent()){
              //console.log(await prof.getText());
              jsonObj = {...jsonObj,prof: await prof.getText()} 
            }
          let phone = element(by.xpath('//*[@id="main"]/ion-list/ion-item[4]/div[1]/div/ion-label/ion-grid/ion-row[4]/ion-col/span'));
          if(await phone.isPresent()){
              //console.log(await phone.getText());
              jsonObj = {...jsonObj,phone: await phone.getText()} 
            }
            
            //console.log(jsonObj);
            arrayTotal.push(jsonObj);
           
        } //fin de for interno
         
          var jsonTotal = JSON.stringify(arrayTotal);
         
         
          fs.writeFile(dir+'/userspage_'+j+'.json',jsonTotal,function(err,file){
            if(err) throw err;
            console.log('Saved!')
           
          })//fin de guardado
          j=j+1;
          var pager= element(by.xpath('//body[1]/ion-app[1]/ng-component[1]/ion-nav[1]/page-listado-solicitudes-prestadora[1]/ion-content[1]/div[2]/ion-grid[1]/ion-row[2]/ion-col[1]/listado-solicitudes[1]/div[1]/div[1]/div[3]/button[1]/span[1]')).click();
          
      
      }while(createdday == date)//fin de do 
    },2500000)//fin de it
})

