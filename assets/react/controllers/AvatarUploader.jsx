import React, { useEffect, useRef, useState } from "react";
import Dropzone from "react-dropzone";
import AvatarEditor from "react-avatar-editor";
import Avatar from "react-avatar";
import useApiAxios from "./redux/requests/useApiAxios";
import { connect } from "react-redux";
import { setErrors, clearErrors } from "./redux/actions/UserActions";

const AvatarUploader = (props) => {
  const [image, setImage] = useState(null);
  const [src, setSrc] = useState();
  const editor = useRef(null);
  const [zoom, setZoom] = useState(() => 1);
  const privateAxios = useApiAxios();

  const handleDrop = (acceptedFiles) => {
    if (acceptedFiles[0]) {
      setImage(acceptedFiles[0]);
    }
  };
  const getImageURL = (canvas) => {
    let imageURL;
    fetch(canvas)
      .then((res) => res.blob())
      .then((blob) => {
        imageURL = URL.createObjectURL(blob);
        setSrc(imageURL);
      });
  };

  const handleSave = async () => {
    if (editor) {
      const canvas = editor.current.getImageScaledToCanvas();
      const canvasb = editor.current.getImage().toDataURL();
      // Send dataURL to the server to save the avatar
      let formData = new FormData();
      formData.append("fileName", `membre_avatar_${props.name.trim()}.png`);
      formData.append("file", canvas.toDataURL());
      privateAxios
        .post("/documents", formData, {
          headers: { "Content-Type": "multipart/form-data" },
        })
        .then(async (res) => {
          if (res.status == 201) {
            props.setAvatar((prev) => ({ ...prev, ...JSON.parse(res.data) }));
            getImageURL(canvasb);
          } else {
            props.setErrors(
              "Error while trying to upload your profile picture"
            );
          }
        })
        .catch((error) => {
          console.log(error);
          props.setErrors(error.message);
        });
      setImage(null);
    }
  };

  useEffect(() => {
    setSrc(props.src);
  }, [props.src]);

  return (
    <div className="mb-3">
      <Dropzone
        onDrop={handleDrop}
        accept={{
          "image/*": [".png", ".jpg", ".jpeg"],
        }}
      >
        {({ getRootProps, getInputProps }) => (
          <div {...getRootProps()}>
            <input {...getInputProps()} />
            <Avatar
              key={new Date().getTime()}
              src={image ? URL.createObjectURL(image) : src}
              size="200"
              round
              style={{ cursor: "pointer" }}
            />
          </div>
        )}
      </Dropzone>
      <div
        className={`modal fade ${image && "show"}`}
        id="exampleModal"
        tabIndex="-1"
        style={{ display: image ? "block" : "none" }}
        aria-labelledby="ModalLabelCropping"
      >
        <div className="modal-dialog">
          <div className="modal-content">
            <div className="modal-header">
              <h1 className="modal-title fs-5" id="ModalLabelCropping">
                Cropping Image
              </h1>
            </div>
            <div className="modal-body">
              <div className="d-flex flex-column justify-center align-items-md-center">
                <AvatarEditor
                  ref={editor}
                  image={image}
                  width={250}
                  height={250}
                  border={50}
                  color={[255, 255, 255, 0.6]}
                  scale={parseInt(zoom)}
                  rotate={0}
                />
                <input
                  type="range"
                  min="1"
                  max="4"
                  step="1"
                  onChange={(e) => setZoom(e.target.value)}
                />
              </div>
            </div>
            <div className="modal-footer">
              <button
                type="button"
                className="btn btn-primary"
                data-bs-dismiss="modal"
                onClick={handleSave}
              >
                Save changes
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

const mapStateToProps = (state) => ({
  error: state.errors,
  isLoading: state.loading,
});

const mapActionsToProps = {
  setErrors,
  clearErrors,
};

export default connect(mapStateToProps, mapActionsToProps)(AvatarUploader);
