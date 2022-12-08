const fs = require('fs');
const path = require('path');
const {browser, element, By}= require('protractor');

describe('Handling table education',()=>{
    beforeAll(async()=>{
        await browser.waitForAngularEnabled(false);
        await browser.get("https://login.abc.gob.ar/nidp/idff/sso?id=ABC-Form&sid=1&option=credential&sid=1&target=https://menu.abc.gob.ar/");
        await browser.manage().window().maximize();
    })
    it('input user and password',async()=>{
        
        await browser.waitForAngularEnabled(false);
        await element(By.id("Ecom_User_ID")).sendKeys('27240122524');
        
        await element(By.id("Ecom_Password")).sendKeys('24012252');
       
        await element(By.linkText('ENTRAR')).click();
        await browser.sleep(1000);
     
         await element(By.linkText('Mis Licencias')).click()
        
    })
    it('enter in the ion area',async()=>{
       
        var windowHandles =    browser.getAllWindowHandles()
        windowHandles.then(function(handles){
            var parentWindow = handles[0];
            var tabbedWindow = handles[1];
            browser.waitForAngularEnabled(true);
            browser.switchTo().window(tabbedWindow);
            element(By.className('label label-md')).click();
            browser.sleep(10000);
          })    
    })

    it('scroll pages',async()=>{
      let date = new Date().toLocaleDateString();
      console.log(date);
      browser.waitForAngularEnabled(true);
      let datetodir = date.split('/').reverse().join('');
      var path ='./jsonfiles/'
      var dir = path+datetodir;
      //console.log(dir);
      if (!fs.existsSync(dir)){   //si no hay directorio crear y j se pone en 0
        fs.mkdirSync(dir);
        var j=0;
        scrap(j,date,dir);
      }else{   //si existe cuento la cantidad de archivos y clickeo hasta la pagina y pongo j al valor de la cantidad de archivos
          /*   let file=[];
          fs.readdir(dir,(err,res)=>{
            try{
                var j= res.length;
                  
                    for(let count=0;count<j;count++){
                      browser.waitForAngularEnabled(true);
                     
                      browser.executeScript('window.scroll(0,2000);').then(()=>{
                           console.log('aca'); browser.sleep(2000);
                      }) 
                      var pager= element(By.xpath('//div[@id="page-buttons-right"]/button[1]')).click();
                      console.log(count);
                    }
                    scrap(j,date,dir);
              } catch(err){
                console.log(err);
              }
                    
                  
            })*/
          } 
         
    },2500000)//fin de it
})
async function scrap(j,date,dir){
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
      assigned.click();
      let diagnostic= element(By.xpath('//b[text()="Diagnóstico"]/following-sibling::span'));
        //console.log(await diagnostic.getText());
        ////ion-list/ion-item[1]/div[1]/div/ion-label/ion-grid/ion-row[6]/ion-col/span
      if(await diagnostic.isPresent()){
          jsonObj = {...jsonObj,diagnostic: await diagnostic.getText()} 
        }
        let address = element(By.xpath("//b[text()='Dirección']/following-sibling::span"));
        //'//*[@id="main"]/ion-list/ion-item[3]/div[1]/div/ion-label/ion-grid/ion-row[2]/ion-col/span[1]'
      if (await address.isPresent()){
          // console.log(await address.getText());
          jsonObj = {...jsonObj,address: await address.getText()} 
        }
      let county = element(By.xpath(" //b[text()='Dirección']/following-sibling::span/following-sibling::span"));
      //'//*[@id="main"]/ion-list/ion-item[3]/div[1]/div/ion-label/ion-grid/ion-row[2]/ion-col/span[2]'
      //b[text()='Dirección']/following-sibling::span/following-sibling::span
      if(await county.isPresent()){
          jsonObj = {...jsonObj,county: await county.getText()} 
        }
      let ocupacion = element(By.xpath("//b[text()='Regimen Estatutario']/following-sibling::span/child::span"));
      if(await ocupacion.isPresent()){
        //console.log(await readec.getText());
          jsonObj = {...jsonObj,ocupacion: await ocupacion.getText()} 
          }
      
      let readec= element(By.xpath("//b[text()='Readecuación de Tareas']/following-sibling::span"));
          //'//*[@id="main"]/ion-list/ion-item[5]/div[1]/div/ion-label/ion-grid/ion-row[1]/ion-col/span'
      if(await readec.isPresent()){
              //console.log(await readec.getText());
          jsonObj = {...jsonObj,readec: await readec.getText()} 
          }
        
      let prof= element(By.xpath("//b[text()='Profesional que emitió la licencia']/following-sibling::span"));
      //'//*[@id="main"]/ion-list/ion-item[5]/div[1]/div/ion-label/ion-grid/ion-row[2]/ion-col'
      if (await prof.isPresent()){
          //console.log(await prof.getText());
          jsonObj = {...jsonObj,prof: await prof.getText()} 
        }
      let matricula = element(By.xpath("//b[text()='Profesional que emitió la licencia']/following-sibling::span/child::span"));
      if(await matricula.isPresent()){
        jsonObj = {...jsonObj,matricula: await matricula.getText()}
      }
      let especialidad = element(By.xpath("//b[text()='Profesional que emitió la licencia']/following-sibling::span/child::span/ancestor::span//br[2]"));
      if(await especialidad.isPresent()){
        jsonObj = {...jsonObj,especialidad: await especialidad.getText()}
      }
      let phone = element(By.xpath("//b[text()='Teléfono']/following-sibling::span"));
      //'//*[@id="main"]/ion-list/ion-item[4]/div[1]/div/ion-label/ion-grid/ion-row[4]/ion-col/span'
      if(await phone.isPresent()){
          //console.log(await phone.getText());
          jsonObj = {...jsonObj,phone: await phone.getText()} 
        }
      let email_of= element(By.xpath("//b[text()='Email Oficial']/following-sibling::span"))  ;
      if ( email_of.isPresent){
        jsonObj = {...jsonObj,email_of: await email_of.getText()}
      }
      
        //console.log(jsonObj);
        arrayTotal.push(jsonObj);
       
    } //fin de for interno*/
     
      var jsonTotal = JSON.stringify(arrayTotal);
     
     
      fs.writeFile(dir+'/userspage_'+j+'.json',jsonTotal,function(err,file){
        if(err) {throw err
          /* $.ajax({
            type: "post",
            url: "url",
            data: "data",
            dataType: "dataType",
            success: function (response) {
              
            }
          }); */
        }else{
          console.log('Saved!');
          
        };
       
      })//fin de guardado

      j=j+1;
      var pager= element(By.xpath('//div[@id="page-buttons-right"]/button[1]')).click();
  
  }while(createdday == date)//fin de do  
}
