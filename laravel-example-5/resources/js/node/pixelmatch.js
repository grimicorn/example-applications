let baselineImageUrl = process.argv[2];
let newImageUrl = process.argv[3];
let diffImagePath = process.argv[4];
let fs = require("fs");
let request = require("request");
let PNG = require("pngjs").PNG;
let pixelmatch = require("pixelmatch");
let baselineImage = request(baselineImageUrl)
    .pipe(new PNG())
    .on("parsed", doneReading);
let newImage = request(newImageUrl)
    .pipe(new PNG())
    .on("parsed", doneReading);
let filesRead = 0;

function doneReading() {
    if (++filesRead < 2) return;
    let diff = new PNG({
        width: baselineImage.width,
        height: baselineImage.height
    });

    let diffs = pixelmatch(
        baselineImage.data,
        newImage.data,
        diff.data,
        baselineImage.width,
        baselineImage.height,
        {
            threshold: 0.1
        }
    );

    diff.pack().pipe(fs.createWriteStream(diffImagePath));

    console.log(
        JSON.stringify({
            success: true,
            difference:
                Math.round((100 * 100 * diffs) / (diff.width * diff.height)) /
                100
        })
    );
}
