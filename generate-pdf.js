// // Importar Puppeteer y FileSystem usando sintaxis ES Modules
// import puppeteer from 'puppeteer';
// import fs from 'fs';

// (async () => {
//     const htmlPath = process.argv[2];
//     const pdfPath = process.argv[3];

//     const browser = await puppeteer.launch();
//     const page = await browser.newPage();

//     // Leer el archivo HTML
//     const htmlContent = fs.readFileSync(htmlPath, 'utf-8');
//     await page.setContent(htmlContent, { waitUntil: 'networkidle0', timeout: 60000 }); // Aumenta el timeout si es necesario

//     // Generar el PDF
//     await page.pdf({
//         path: pdfPath,
//         format: 'A4',
//         printBackground: true,
//         margin: {
//             top: '10mm',
//             bottom: '10mm',
//             left: '10mm',
//             right: '10mm',
//         },
//     });

//     await browser.close();
// })();


// import puppeteer from 'puppeteer';
// import fs from 'fs';

// (async () => {
//     const htmlPath = process.argv[2];
//     const pdfPath = process.argv[3];

//     try {
//         // Verificar que el archivo HTML existe
//         if (!fs.existsSync(htmlPath)) {
//             console.error(`Error: El archivo HTML no existe en la ruta ${htmlPath}`);
//             process.exit(1);
//         }

//         const browser = await puppeteer.launch();
//         const page = await browser.newPage();

//         // Leer el archivo HTML
//         const htmlContent = fs.readFileSync(htmlPath, 'utf-8');
//         await page.setContent(htmlContent, { waitUntil: 'networkidle0', timeout: 60000 });

//         // Generar el PDF
//         await page.pdf({
//             path: pdfPath,
//             format: 'A4',
//             printBackground: true,
//             margin: {
//                 top: '10mm',
//                 bottom: '10mm',
//                 left: '10mm',
//                 right: '10mm',
//             },
//         });

//         await browser.close();
//     } catch (error) {
//         console.error(`Error al generar el PDF: ${error.message}`);
//         process.exit(1);
//     }
// })();


import puppeteer from 'puppeteer';
import fs from 'fs';

(async () => {
    const htmlPath = process.argv[2];
    const pdfPath = process.argv[3];

    try {
        // Verificar que el archivo HTML existe
        if (!fs.existsSync(htmlPath)) {
            console.error(`Error: El archivo HTML no existe en la ruta ${htmlPath}`);
            process.exit(1);
        }

        const browser = await puppeteer.launch({
            headless: true, // Cambia a false si quieres ver el navegador
            args: ['--no-sandbox', '--disable-setuid-sandbox'], // Argumentos adicionales para evitar problemas de permisos
        });
        const page = await browser.newPage();

        // Leer el archivo HTML
        const htmlContent = fs.readFileSync(htmlPath, 'utf-8');
        await page.setContent(htmlContent, { waitUntil: 'networkidle0', timeout: 60000 }); // Aumenta el timeout si es necesario

        // Generar el PDF
        await page.pdf({
            path: pdfPath,
            format: 'A4',
            printBackground: true,
            margin: {
                top: '10mm',
                bottom: '10mm',
                left: '10mm',
                right: '10mm',
            },
        });

        await browser.close();
        console.log(`PDF generado correctamente en ${pdfPath}`);
    } catch (error) {
        console.error(`Error al generar el PDF: ${error.message}`);
        process.exit(1);
    }
})();