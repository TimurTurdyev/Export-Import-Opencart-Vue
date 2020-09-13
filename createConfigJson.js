const fs = require("fs");
let fileContent = fs.readFileSync("dist/report.json", "utf8");
let parsedData = JSON.parse(fileContent);
let createInfo = parsedData.entrypoints.app.assets

let css = createInfo.filter(function(name) {
    return name.match(/css/)
});

let js = createInfo.filter(function(name) {
    return name.match(/js/)
});

fs.writeFileSync("dist/link.json", JSON.stringify({ css, js }))