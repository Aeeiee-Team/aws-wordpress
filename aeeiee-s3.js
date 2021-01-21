// initialize AWS SDK
var s3 = new AWS.S3({
  apiVersion: "latest",
  accessKeyId: aws_vars.accessKeyId,
  secretAccessKey: aws_vars.secretAccessKey,
  region: "eu-west-2",
});
const bucketName = "aeeiee-test";

console.log(vars);
jQuery(document).ready(function ($) {
  const fileUploadInput = $("#file-uploader");
  const messageSection = $(".message");
  const objectsSection = $("#objects");

  $("#start-upload").on("click", function () {
    const file = fileUploadInput[0].files[0];

    messageSection.html("Starting your file Upload to AWS S3....");

    var upload = new AWS.S3.ManagedUpload({
      service: s3,
      params: {
        Body: file,
        Bucket: bucketName,
        Key: file.name,
      },
    });

    //	start the upload
    upload.send(function (err, data) {
      if (err) {
        console.log("Error", err.code, err.message);
        alert("There was an error uploading the file, please try again");
      } else {
        console.log(data);
        messageSection.html("File successfully uploaded to S3");
      }
    });
  });

  // Call S3 to obtain a list of the objects in the bucket
  s3.listObjects({ Bucket: bucketName }, function (err, data) {
    if (err) {
      console.log("Error", err);
    } else {
      console.log("Success", data.Contents);
      data.Contents.map((content) => {
        objectsSection.append(
          `<li><a href="${getPresignedURL(content.Key)}">${
            content.Key
          }</a></li>`
        );
      });
    }
  });

  function getPresignedURL(key) {
    return s3.getSignedUrl("getObject", {
      Bucket: bucketName,
      Key: key,
      Expires: 120,
    });
  }
});
