const fs = require('fs');
const path = require('path');

const {browser, element, By}= require('protractor');

describe('Handling table education',()=>{
    beforeAll(async()=>{
        browser.waitForAngularEnabled(false);
        await browser.get("https://login.abc.gob.ar/nidp/idff/sso?id=ABC-Form&sid=1&option=credential&sid=1&target=https://menu.abc.gob.ar/");
        await browser.manage().window().maximize();
    })
    it('input user and password',async()=>{
        
        await browser.waitForAngularEnabled(false);
        element(By.id("Ecom_User_ID")).sendKeys('27240122524');
        
        element(By.id("Ecom_Password")).sendKeys('24012252');
       
       element(By.linkText('ENTRAR')).click();
       browser.sleep(5000); 
       element(By.linkText('Mis Licencias')).click();
       browser.sleep(5000)

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
      
      
      //browser.executeScript("window.sessionStorage.getItem('contador')==0")
      //browser.executeScript("var j=0;window.sessionStorage.setItem('contador',j)")
     
      let datetodir = date.split('/').reverse().join('');
      var path ='./jsonfiles/'
      var dir = path+datetodir;
      //console.log(dir);
      if (!fs.existsSync(dir)){   //si no hay directorio crear y j se pone en 0
        fs.mkdirSync(dir);
        var j=0;
        scrap(j,date,dir);
      }else{   //si existe cuento la cantidad de archivos y clickeo hasta la pagina y pongo j al valor de la cantidad de archivos
            let file=[];
          fs.readdir(dir,(err,res)=>{
            if(err){
              console.log(err);
            }else{
                    file= res;
                    //console.log(file.length);
                    var j= file.length;
                    browser.waitForAngularEnabled(true);
                    for(let count=0;count<j;count++){
                      //console.log (count);
                      element(By.xpath('//body[1]/ion-app[1]/ng-component[1]/ion-nav[1]/page-listado-solicitudes-prestadora[1]/ion-content[1]/div[2]/ion-grid[1]/ion-row[2]/ion-col[1]/listado-solicitudes[1]/div[1]/div[1]/div[3]/button[1]/span[1]')).click();
                      browser.sleep(5000);
                    }
                    scrap(j,date,dir);
                  }
            })
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
        if(err) throw err;
        console.log('Saved!')
       
      })//fin de guardado

      j=j+1;
      //browser.executeScript("var j=parseInt(window.sessionStorage.getItem('contador'))+1;;window.sessionStorage.setItem('contador',j)");
      var pager= element(By.xpath('//body[1]/ion-app[1]/ng-component[1]/ion-nav[1]/page-listado-solicitudes-prestadora[1]/ion-content[1]/div[2]/ion-grid[1]/ion-row[2]/ion-col[1]/listado-solicitudes[1]/div[1]/div[1]/div[3]/button[1]/span[1]')).click();
      
  
  }while(createdday == date)//fin de do  
}

