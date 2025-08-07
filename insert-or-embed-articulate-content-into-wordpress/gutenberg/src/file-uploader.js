/**
 * Internal block libraries
 */

const { __ } = wp.i18n;

const {
	ButtonGroup,
	Button
} = wp.components;

const {
	Component,
	Fragment
} = wp.element;

class FileUploader extends Component {
	constructor() {
		super( ...arguments );

		this.state = {
			notice: true
		};
	}

	componentDidMount() {
		console.log('FileUploader: componentDidMount called');

		// Use a singleton pattern - only create one uploader instance
		if (window.articulateUploader && window.articulateUploader.initialized) {
			console.log('FileUploader: Reusing existing uploader instance');
			this.uploader = window.articulateUploader;
			return;
		}

		// Clean up any existing uploader instances
		if (window.articulateUploader) {
			console.log('FileUploader: Destroying existing global uploader');
			try {
				window.articulateUploader.destroy();
			} catch (e) {
				console.log('FileUploader: Error destroying existing uploader:', e);
			}
			window.articulateUploader = null;
		}

		// Remove any existing file inputs
		document.querySelectorAll('input[type="file"][id*="html5_"]').forEach(function(input) {
			console.log('FileUploader: Removing existing file input:', input.id);
			input.remove();
		});

		console.log('FileUploader: Creating new plupload instance');
		console.log('FileUploader: articulateOptions available:', typeof articulateOptions !== 'undefined');
		if (typeof articulateOptions !== 'undefined') {
			console.log('FileUploader: articulateOptions.ajax_url:', articulateOptions.ajax_url);
			console.log('FileUploader: articulateOptions._nonce_upload_file:', articulateOptions._nonce_upload_file);
		}

		this.uploader = new plupload.Uploader({
			runtimes: 'html5,flash,silverlight,html4',
			'browse_button': 'chunkFileUpload',
			container: document.getElementById( 'articulate_uploader' ),
			url: articulateOptions.ajax_url,
			'chunk_size': articulateOptions.plupload.chunk_size,
			'max_retries': articulateOptions.plupload.max_retries,
			'dragdrop': true,
			'multi_selection': false,
			'file_data_name': 'async-upload',
			'multipart_params': {
				'_ajax_nonce': articulateOptions._nonce_upload_file,
				'action': 'articulate_upload_file'
			},
			filters: {
				'max_file_size': '0',
				'mime_types': [
					{
						title: 'Zip files',
						extensions: 'zip'
					},
					{
						title: 'MP4 files',
						extensions: 'mp4'
					}
				]
			},
			init: {
				PostInit: () => {
					console.log('FileUploader: PostInit called, setting up upload button');
					const uploadButton = document.getElementById( 'chunkFileUploadButton' );
					if (uploadButton) {
						console.log('FileUploader: Upload button found, adding click handler');
						uploadButton.onclick = function() {
							console.log('FileUploader: Upload button clicked, starting upload');
							this.uploader.start();
						}.bind(this);
					} else {
						console.error('FileUploader: Upload button not found!');
					}
				},

				UploadProgress: ( up, file ) => {
					console.log('FileUploader: Upload progress:', up.total.percent + '%');
					if ( 100 === up.total.percent ) {
						console.log('FileUploader: Upload complete, unzipping content');
						document.getElementById( 'fileArea' ).innerHTML = `${ file.name } uploaded. Unzipping content.`;
					} else {
						document.getElementById( 'fileArea' ).innerHTML = `${ file.name } is uploading at ${ up.total.percent }%`;
					}
				},

				FileUploaded: ( upldr, file, object ) => {
					console.log('FileUploader: FileUploaded called');
					console.log('FileUploader: Response object:', object);
					try {
						const info = jQuery.parseJSON( object.response );
						console.log('FileUploader: Parsed response:', info);
						document.getElementById( 'fileArea' ).innerHTML = info.info;
						if ( 1 === info.OK ) {
							console.log('FileUploader: Upload successful, calling insertData');
							this.props.insertData( info );
							this.setState({ notice: false });
							setTimeout(
								() => {
									const notice = document.getElementById( 'trial-notice' );
									if ( null !== notice ) {
										notice.classList.add( 'hide-notice' );
									}
								},
								500
							);
						} else {
							console.log('FileUploader: Upload failed, OK =', info.OK);
						}
					} catch (e) {
						console.error('FileUploader: Error parsing response:', e);
						console.log('FileUploader: Raw response:', object.response);
					}
				},

				FilesAdded: ( up, files ) => {
					console.log('FileUploader: FilesAdded called');
					console.log('FileUploader: Number of files:', this.uploader.files.length);
					if ( 1 < this.uploader.files.length ) {
						console.log('FileUploader: Removing previous file');
						this.uploader.removeFile( this.uploader.files[0]);
					}
					const fileName = this.uploader.files[0].name;
					const fileSize = ( ( this.uploader.files[0].size / 1024 ) / 1024 ).toFixed( 1 );
					console.log('FileUploader: File selected:', fileName, 'Size:', fileSize, 'mb');
					document.getElementById( 'fileArea' ).innerHTML = `${ fileName } (${ fileSize } mb)`;
				},

				Error: ( up, err ) => {
					console.error('FileUploader: Upload error:', err);
					console.error('FileUploader: Error details:', {
						code: err.code,
						message: err.message,
						file: err.file ? err.file.name : 'unknown'
					});
				}
			}
		});

		console.log('FileUploader: Initializing uploader');
		this.uploader.init();
		console.log('FileUploader: Uploader initialized successfully');
		this.uploader.initialized = true;
		window.articulateUploader = this.uploader;
		
		// Add a small delay to ensure the file input is properly set up
		setTimeout(() => {
			const fileInput = document.querySelector('input[type="file"][id*="html5_"]');
			if (fileInput) {
				console.log('FileUploader: File input found and ready:', fileInput.id);
			} else {
				console.error('FileUploader: No file input found after initialization');
			}
		}, 100);
	}

	componentWillUnmount() {
		console.log('FileUploader: componentWillUnmount called');
		// Don't destroy the uploader, just clear the local reference
		// The global uploader will persist
		this.uploader = null;
	}

	render() {
		return (
			<Fragment>
				<ButtonGroup id="articulate_uploader">
					<Button
						className="material-btn grey"
						id="chunkFileUpload"
					>
						{ __( 'Choose your zip file' ) }
					</Button>

					<Button
						id="chunkFileUploadButton"
						className="material-btn"
						isBusy={ this.isUploading ? true : false }
					>
						<i class="upload-icon">call_made</i>{ __( 'Upload!' ) }
					</Button>
				</ButtonGroup>
				<p class="fileArea" id="fileArea"></p>

			</Fragment>
		);
	}
}

export default FileUploader;
