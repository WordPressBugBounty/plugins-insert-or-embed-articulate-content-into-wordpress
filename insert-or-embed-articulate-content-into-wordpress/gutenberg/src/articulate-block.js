/**
 * Block dependencies
 */
import './style.scss';

import FileUploader from '../src/file-uploader.js';

/**
 * Internal block libraries
 */
const { __ } = wp.i18n;

const { apiFetch, apiRequest } = wp;

const {
	Button,
	ButtonGroup,
	Dashicon,
	Modal,
	PanelBody,
	Placeholder,
	RadioControl,
	SelectControl,
	Spinner,
	TextControl
} = wp.components;

const {
	Component,
	Fragment
} = wp.element;

class ArticulateBlock extends Component {

	constructor() {
		super( ...arguments );

		this.getCount = this.getCount.bind( this );
		this.getLibrary = this.getLibrary.bind( this );
		this.deleteLibrary = this.deleteLibrary.bind( this );
		this.insertData = this.insertData.bind( this );
		this.insertUpload = this.insertUpload.bind( this );
		this.changeIcon = this.changeIcon.bind( this );

		this.state = {
			options: {
				type: 'iframe',
				iFrameOption: 'default',
				ratio: '4:3',
				lightboxTitleType: 'default',
				linkType: 'default'
			},
			isUploadOpen: false,
			isLibraryOpen: false,
			data: [],
			tempData: null,
			isLoaded: false,
			dir: articulateOptions.dir,
			count: articulateOptions.count
		};
	}

	makeFormData( obj ) {
		let fd = new FormData();
		for ( const key in obj ) {
			fd.append( key, obj[key]);
		}
		return fd;
	}

	async getCount() {
		const response = await apiFetch({ url: articulateOptions.ajax_url + '?action=articulate_get_dir_data', method: 'get' });
		const data = response.dir_list;
		this.setState({
			dir: data.length
		});
	}

	async getLibrary() {
		const response = await apiFetch({ url: articulateOptions.ajax_url + '?action=articulate_get_dir_data', method: 'get' });
		const data = response.dir_list;
		this.setState({
			data,
			isLoaded: true
		});
	}

	async deleteLibrary( item ) {
		this.setState({ isLoaded: false });
		let fd = this.makeFormData({
			'action': 'del_dir',
			'_ajax_nonce': articulateOptions._nonce_del_dir,
			'dir': item,
			'return_dir_list': 1
		});
		const response = await apiFetch({ url: articulateOptions.ajax_url, method: 'post', body: fd });
		if ( 'fail' == response.status ) {
			return;
		}
		const data = response.dir_list;
		if ( this.props.attributes.src !== undefined && this.props.attributes.src.includes( item ) ) {
			this.props.setAttributes({
				src: '',
				href: ''
			});
		}

		this.setState({
			dir: data.length
		});

		this.setState({
			data,
			isLoaded: true
		});
	}

	insertData( data ) {
		this.setState({ tempData: data });
		this.getCount();
	}

	async insertUpload() {
		const { tempData, options } = this.state;
		let fd = this.makeFormData({
			'action': 'rename_dir',
			'_ajax_nonce': articulateOptions._nonce_rename_dir,
			'dir_name': tempData.folder,
			'title': ( 'undefined' != typeof tempData.newFolder ) ? tempData.newFolder : ''
		});
		const data = await apiFetch({ url: articulateOptions.ajax_url, method: 'post', body: fd });

		if ( undefined !== data ) {
			if ( 'success' == data[0]) {
				tempData.newFolder = data[1];
			}
			const path = tempData.path.replace( tempData.folder, ( tempData.newFolder || tempData.folder ) );
			options.src = path;
			options.href = path;
			const attributes = { ...options };
			this.props.setAttributes({ ...attributes });
			this.setState({
				isUploadOpen: false,
				tempData: null
			});
		}
	}

	changeIcon() {
		setTimeout(
			() => {
				const el = document.querySelectorAll( '.modal-collect .components-panel__body' );
				Object.keys( el ).forEach( i => {
					if ( el[i].classList.contains( 'is-opened' ) ) {
						el[i].nextElementSibling.classList.remove('dashicons-visibility');
						el[i].nextElementSibling.classList.add('dashicons-external');
					} else {
						el[i].nextElementSibling.classList.remove('dashicons-external');
						el[i].nextElementSibling.classList.add('dashicons-visibility');
					}
				});
			}, 200
		);
	}

	initMaterializeSelect() {
		setTimeout( () => {
			let _elems = document.querySelectorAll( '.elearning-block-scope .input-field select:not(.materialize__done)' );

			for ( let i = 0; i < _elems.length; i++ ) {
				_elems[i].classList.add( 'materialize__done' );
			}
		}, 500 );

		setTimeout( () => {
			let _elems = document.querySelectorAll( '.elearning-block-scope .input-field select:not(.materialize__done)' );

			for ( let i = 0; i < _elems.length; i++ ) {
				_elems[i].classList.add( 'materialize__done' );
			}

		}, 1000 );

		setTimeout( () => {
			let _elems = document.querySelectorAll( '.elearning-block-scope .input-field select:not(.materialize__done)' );

			for ( let i = 0; i < _elems.length; i++ ) {
				_elems[i].classList.add( 'materialize__done' );
			}
		}, 1500 );

		setTimeout( () => {
			let _elems = document.querySelectorAll( '.elearning-block-scope .input-field select:not(.materialize__done)' );

			for ( let i = 0; i < _elems.length; i++ ) {
				_elems[i].classList.add( 'materialize__done' );
			}
		}, 2000 );

		setTimeout( () => {
			let _elems = document.querySelectorAll( '.quiz-insert-as-options-box input[value="lightbox"]:not(.materialize__done), .quiz-insert-as-options-box input[value="open_link_in_new_window"]:not(.materialize__done), .quiz-insert-as-options-box input[value="open_link_in_same_window"]:not(.materialize__done)' );
			for ( let i = 0; i < _elems.length; i++ ) {
				_elems[i].setAttribute( 'disabled', 'disabled' );
				_elems[i].classList.add( 'materialize__done' );
			}

			let _elems2 = document.querySelectorAll( '.quiz-size-options-box ul.select-dropdown li span:not(.materialize__done)' );
			for ( let i = 0; i < _elems2.length; i++ ) {
				if ( 'Default' != _elems2[i].innerHTML  ) {
					_elems2[i].parentElement.classList.add( 'disabled' );
				}
				_elems2[i].classList.add( 'materialize__done' );
			}

		}, 500 );
	}


	render() {
		const { options, isUploadOpen, isLibraryOpen, data, tempData, isLoaded } = this.state;

		return (
			<Fragment>
				<div className="elearning-block-scope">
					<Placeholder
						icon="welcome-learn-more"
						label={ __( 'e-Learning' ) }
					>
						{	this.props.attributes.src ?
							<Fragment>
								{ this.props.attributes.src }
								<ButtonGroup>
									<Button
										className="material-btn grey"
										onClick={ () => this.props.setAttributes({
											src: '',
											href: ''
										}) }
									>
										{ __( 'Remove' ) }
									</Button>
									<Button
										className="material-btn"
										onClick={ () => {
											this.getLibrary();
											this.setState({ isLibraryOpen: true });
										} }
									>
										{ __( 'Choose Another' ) }
									</Button>
								</ButtonGroup>
							</Fragment> :
							<Fragment>
								<span>{ __( 'Upload a .zip or .mp4 file that you published from your tool or choose an existing content item.' ) }</span>
								<ButtonGroup>
									<Button
										className="material-btn grey"
										onClick={ () => this.setState({ isUploadOpen: true }) }
									>
										{ __( 'Upload' ) }
									</Button>
									<Button
										className="material-btn"
										onClick={ () => {
											this.getLibrary();
											this.setState({ isLibraryOpen: true });
										} }
									>
										{ __( 'Content Library' ) }
									</Button>
								</ButtonGroup>
							</Fragment>
						}
					</Placeholder>

					{ isUploadOpen &&
					<Modal
						title={ __( '' ) }
						className="elearning-modal elearning-block-scope"
						onRequestClose={ () => this.setState({ isUploadOpen: false }) }
						shouldCloseOnClickOutside={false}
					>

						{ this.initMaterializeSelect() }

						<h2 class="header-upload-file">{ __( 'Upload File' ) }</h2>

						<FileUploader insertData={ this.insertData } options={ this.state } />

						{ ( null !== tempData ) &&
								<div className="collectionUpload">
									<TextControl
										label={ __( 'Title:' ) }
										className="top-margin small-title"
										type="text"
										value={ tempData.newFolder !== undefined ? tempData.newFolder : tempData.folder }
										onChange={ e => {
											tempData.newFolder = e;
											this.setState({ tempData });
										}}
									/>

									<RadioControl
										label={ __( 'Insert As:' ) }
										className="top-margin large-title quiz-insert-as-options-box"
										selected={ ( 'iframe_responsive' == options.type || ! options.type ) ? 'iframe' : options.type }
										options={ [
											{ label: 'iFrame', value: 'iframe' },
											{ label: 'Lightbox (Paid Feature)', value: 'lightbox' },
											{ label: 'Link that opens in a new window (Paid Feature)', value: 'open_link_in_new_window' },
											{ label: 'Link that opens in a same window (Paid Feature)', value: 'open_link_in_same_window' }
										] }
										onChange={ e => {
											options.type = e;
											this.setState({ options });
										} }
									/>

									<ButtonGroup>
										<Button
											className="material-btn top-margin"
											onClick={ this.insertUpload }
										>
											{ __( 'Insert' ) }
										</Button>
									</ButtonGroup>
									<p>
										<iframe src="https://www.elearningfreak.com/wordpresspluginlatesttrial500.html?v=43000000024&editor=gutenburg" width="600px" frameborder="0"></iframe>
									</p>
								</div>
						}

						<iframe width="600" height="338" src="https://www.youtube.com/embed/exojBaymRkw" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>


					</Modal>
					}

					{ isLibraryOpen &&
					<Modal
						title={ __( '' ) }
						className="elearning-modal elearning-block-scope"
						onRequestClose={ () => this.setState({ isLibraryOpen: false }) }
						shouldCloseOnClickOutside={false}
					>
						{this.initMaterializeSelect()}

						{ ( true !== isLoaded ) ?
							<Placeholder>
								<Spinner />
							</Placeholder> :

							( null !== data ) ?
								<div className="collection">
									<div class="collection-header">
										<h4>{ __( 'Content Library' ) }</h4>
									</div>
									{ Object.keys( data ).map( i => {
										return (
											<div className="modal-collect" onClick={ () => {
												this.changeIcon();
												this.initMaterializeSelect();
											} }>
												<PanelBody
													title={ data[i].dir }
													initialOpen={ false }
													key={ i }
												>
													<RadioControl
														label={ __( 'Insert As' ) }
														className="top-margin large-title quiz-insert-as-options-box"
														selected={ ( 'iframe_responsive' == options.type || ! options.type ) ? 'iframe' : options.type }
														options={ [
															{ label: 'iFrame', value: 'iframe' },
															{ label: 'Lightbox (Paid Feature)', value: 'lightbox' },
															{ label: 'Link that opens in a new window (Paid Feature)', value: 'open_link_in_new_window' },
															{ label: 'Link that opens in a same window (Paid Feature)', value: 'open_link_in_same_window' }
														] }
														onChange={ e => {
															options.type = e;
															this.setState({ options });
														} }
													/>

													
													<ButtonGroup>
														<Button
															className="material-btn top-margin"
															onClick={ () => {
																options.src = `${ data[i].path + data[i].dir }/${ data[i].file }`;
																options.href = `${ data[i].path + data[i].dir }/${ data[i].file }`;
																const attributes = { ...options };
																this.props.setAttributes({ ...attributes });
																this.setState({ isLibraryOpen: false });
															} }
														>
															{ __( 'Insert' ) }
														</Button>

														<Button
															icon="trash"
															label={ __( 'Delete' ) }
															className="top-margin delete-icon-button"
															onClick={ () => {
																const consent = confirm( __( 'Are you sure you want to do this?' ) );
																if ( consent ) {
																	this.deleteLibrary( data[i].dir );
																}
															}}
														/>
													</ButtonGroup>
													<p>
														<iframe src="https://www.elearningfreak.com/wordpresspluginlatesttrial500.html?v=43000000024" width="600px" frameborder="0"></iframe>
													</p>
												</PanelBody>

												<Dashicon
													className="floating-eye"
													icon="visibility"
												/>

												<Button
													icon="trash"
													label={ __( 'Delete' ) }
													className="top-margin delete-icon-button float"
													onClick={ () => {
														const consent = confirm( __( 'Are you sure you want to do this?' ) );
														if ( consent ) {
															this.deleteLibrary( data[i].dir );
														}
													}}
												/>
											</div>
										);
									}) }
								</div> :
								<Fragment>
									<p>{ __( 'Empty.  Please upload content.' ) }</p>

									<Button
										className="material-btn grey no-margin"
										onClick={ () => {
											this.setState({
												isUploadOpen: true,
												isLibraryOpen: true
											});
										} }
									>
										{ __( 'Upload' ) }
									</Button>
								</Fragment>

						}
					</Modal>
					}
				</div>
			</Fragment>
		);
	}
}

export default ArticulateBlock;
