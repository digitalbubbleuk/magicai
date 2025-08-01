/**
 * @typedef {Object} EditingImage
 * @property {string | null} output
 * @property {string | null} name
 */

/**
 * @type {import('alpinejs').AlpineComponent}
 * @param {Object} options
 * @param {object[]} options.tools
 * @param {string[]} options.primaryToolKeys
 */
export default (options = {}) => {
	return ({
		prevViews: [],
		task: false,
		currentView: 'home',
		sidebarCollapsed: false,
		toolbarCollapsed: false,
		modalShow: false,
		activeModal: null,
		activeModalId: null,
		activeModalIdPrefix: null,
		currentToolsCat: 'primary',
		tools: options.tools || [],
		primaryToolKeys: options.primaryToolKeys || [],
		showImageDetails: false,
		zoomLevel: 1,
		reachedMinZoom: false,
		reachedMaxZoom: false,
		newItems: [],
		showNotif: false,
		editingImageDimensions: {
			width: 0,
			height: 0,
		},
		painting: false,
		brushSize: 40,
		canvasCtx: null,
		lastUploadingImages: [],
		uploadingImages: [],
		promptLibraryShow: false,
		promptFilter: 'all',
		searchPromptStr: '',
		supportMultipleImageTools: [
			'reimagine',
		],
		/**
		 * Do not use these properties directly, use the getters and setters instead.
		 */
		_busy: false,
		_template: '',
		_prompt: '',
		_selectedTemplateDescription: '',
		_selectedPromptDescription: '',
		_selectedTool: '',
		aiModel: '',
		/** @type {EditingImage} */
		_prevEditingImage: {},
		/** @type {EditingImage} */
		_editingImage: {},

		get busy() {
			return this._busy;
		},
		set busy(value) {
			this._busy = value;
		},

		get selectedTemplate() {
			return this._template.trim();
		},
		set selectedTemplate(value) {
			this._template = value;
			this.$refs.promptInput?.focus();
		},

		get prompt() {
			return this._prompt;
		},
		set prompt(value) {
			this._prompt = value.trim();
			this.$refs.promptInput?.focus();
		},

		get selectedTemplateDescription() {
			return this._selectedTemplateDescription;
		},
		set selectedTemplateDescription(value) {
			if (this._selectedTemplateDescription === value) return;
			this._selectedTemplateDescription = value;
		},

		get selectedPromptDescription() {
			return this._selectedPromptDescription;
		},
		set selectedPromptDescription(value) {
			if (this._selectedPromptDescription === value) return;
			this._selectedPromptDescription = value;
		},

		get selectedTool() {
			return this._selectedTool;
		},
		set selectedTool(value) {
			this.aiModel = AIModelsforTool[value];

			if (this._selectedTool === value) return;

			this._selectedTool = value;

			this.makeCanvasEditable(this._selectedTool === 'sketch_to_image' ? { width: 1024, height: 1024 } : {});

			if ( !this.selectedToolSupportMultiImagesUpload() && this.$refs.editorFileInput.files.length ) {
				this.clearImageInputs();
			}
		},

		get prevEditingImage() {
			return this._prevEditingImage;
		},

		get editingImage() {
			return this._editingImage;
		},
		/**
		 * @param {EditingImage} obj
		 */
		set editingImage(obj) {
			this._prevEditingImage = this._editingImage;
			this._editingImage = obj;

			this.showImageDetails = false;
			this.zoomLevel = 1;

			if ( obj?.output ) {
				fetch(obj.output)
					.then(response => response.blob())
					.then(blob => {
						const file = new File([ blob ], obj.title?.split('.')?.at(0) || 'image', { type: blob.type });
						const dataTransfer = new DataTransfer();
						dataTransfer.items.add(file);

						this.$refs.uploadedImageInput.files = dataTransfer.files;
					})
					.catch(error => {
						toastr.error('Failed to load image');
						console.error('Error creating file from URL:', error);
					});
			}
		},

		init() {
			this.onViewChange = this.onViewChange.bind(this);
			this.onZoomLevelChange = this.onZoomLevelChange.bind(this);
			this.onCreativeSuiteStageInitiated = this.onCreativeSuiteStageInitiated.bind(this);
			this.makeCanvasEditable = this.makeCanvasEditable.bind(this);
			this.startPainting = this.startPainting.bind(this);
			this.stopPainting = this.stopPainting.bind(this);
			this.paint = this.paint.bind(this);

			document.documentElement.style.scrollbarGutter = 'stable';

			this.$watch('currentView', this.onViewChange);
			this.$watch('zoomLevel', this.onZoomLevelChange);

			if ( this.creativeSuite ) {
				this.$watch('creativeSuite.stageInitiated', this.onCreativeSuiteStageInitiated);
			}

			const urlParams = new URLSearchParams(window.location.search);
			if (urlParams.has('action')) {
				const action = urlParams.get('action');
				let tools = [ 'merge_face', 'uncrop', 'reimagine', 'remove_background', 'cleanup', 'upscale', 'replace_background', 'sketch_to_image', 'remove_text', 'inpainting', 'style_transfer', 'image_relight' ];
				if (tools.includes(action)) {
					this.currentView = 'editor';
					this.switchToolsCat({ toolKey: action });
					this.selectedTool = action;
				}
			}
		},

		switchView(view) {
			if ( this.creativeSuite?.editingTextNode ) return;

			if (view === '<') {
				this.currentView = this.prevViews.pop() || 'home';
				return;
			}

			this.prevViews.push(this.currentView);
			this.currentView = view || 'home';
		},

		switchSidebarCollapsed(collapsed) {
			if (collapsed != null) {
				this.sidebarCollapsed = collapsed;
				return;
			}

			this.sidebarCollapsed = !this.sidebarCollapsed;

			this.$nextTick(() => {
				this.zoomLevel > 1 && this.fitToScreen();
			});
			this.$refs.imageEditorSidebar.addEventListener('transitionend', event => {
				if (event.target !== this.$refs.imageEditorSidebar && event.propertyName !== 'transform') return;

				this.reachedMaxZoom && this.fitToScreen();
			});
		},

		onViewChange(view) {
			const isEditor = view === 'editor';

			document.documentElement.style.overflow = isEditor ? 'hidden' : '';
		},

		setActiveModal(data, idPrefix = 'modal') {
			this.activeModal = data;
			this.activeModalId = data.id;
			this.activeModalIdPrefix = idPrefix;
		},
		prevImageModal() {
			const currentEl = document.querySelector(`.image-result[data-id='${this.activeModalId}'][data-id-prefix=${this.activeModalIdPrefix}]`);
			const prevEl = currentEl?.previousElementSibling;
			if (!prevEl) return;
			const data = JSON.parse(prevEl.getAttribute('data-payload') || {});
			this.setActiveModal(data, currentEl.getAttribute('data-id-prefix'));
		},
		nextImageModal() {
			const currentEl = document.querySelector(`.image-result[data-id='${this.activeModalId}'][data-id-prefix=${this.activeModalIdPrefix}]`);
			const nextEl = currentEl?.nextElementSibling;
			if (!nextEl) return;
			const data = JSON.parse(nextEl.getAttribute('data-payload') || {});
			this.setActiveModal(data, currentEl.getAttribute('data-id-prefix'));
		},

		getSelectedToolCat() {
			return this.getToolCat(this.selectedTool);
		},
		switchToolsCat(options = {}) {

			if (options.cat) {
				return this.currentToolsCat = options.cat;
			}

			if (options.toolKey) {
				return this.currentToolsCat = this.getToolCat(options.toolKey);
			}

			if (!options.cat && !options.toolKey) {
				return this.currentToolsCat = this.currentToolsCat === 'primary' ? 'secondary' : 'primary';
			}
		},
		getToolCat(tool) {
			return this.primaryToolKeys.findIndex(key => key === tool) >= 0 ? 'primary' : 'secondary';
		},
		selectedToolSupportMultiImagesUpload() {
			return this.supportMultipleImageTools.includes(this.selectedTool) && this.aiModel === 'openai';
		},

		handleDragOver() {
			this.$refs.dropArea.classList.add('drag-over');
		},
		handleDragLeave() {
			this.$refs.dropArea.classList.remove('drag-over');
		},
		handleUploadingMultiImages(files) {
			if ( !this.selectedToolSupportMultiImagesUpload() ) {
				return;
			}

			const filesArray = Array.from(files);
			const existingFiles = Array.from(this.lastUploadingImages);
			const dataTransfer = new DataTransfer();

			existingFiles.forEach(file => {
				dataTransfer.items.add(file);
			});

			filesArray.forEach(file => {
				if (!file.type.startsWith('image/')) {
					toastr.error('Please upload a valid image file.');
					this.clearImageInputs();
					return;
				}

				if ( existingFiles.findIndex(existingFile => existingFile.name === file.name && existingFile.size === file.size) === -1 ) {
					dataTransfer.items.add(file);
				}
			});

			this.$refs.editorFileInput.files = dataTransfer.files;
			this.$refs.uploadedImageInput.files = dataTransfer.files;
			this.lastUploadingImages = dataTransfer.files;

			this.uploadingImages = Array.from(dataTransfer.files).map(file => ({
				src: URL.createObjectURL(file),
				name: file.name
			}));

			return dataTransfer.files;
		},
		handleFileChange(event) {
			let files = event.dataTransfer ? event.dataTransfer.files : event.target?.files;

			this.$refs.dropArea.classList.remove('drag-over');

			if ( !files ) {
				return;
			}

			if ( event.dataTransfer ) {
				this.$refs.editorFileInput.files = files;
			}

			// in case if user opened the file dialog and closed it without selecting any files
			if (files && !files.length) {
				this.handleUploadingMultiImages(this.lastUploadingImages);
				return;
			}

			if (this.selectedToolSupportMultiImagesUpload()) {
				files = this.handleUploadingMultiImages(files);
			}

			this.handleFiles(files);
		},
		// TODO: handle files array
		handleFiles(files) {
			if ( !files[0] ) return;

			if ( !this.selectedToolSupportMultiImagesUpload() )  {
				this.editingImage = {
					output: URL.createObjectURL(files[0]),
					name: files[0].name
				};
			}

			const formData = new FormData();

			formData.append('image', files[0]);
			formData.append('reimagine', files[0]);

			if ([ 'reimagine' ].includes(this.selectedTool)) {
				if ( this.$refs.promptInput ) {
					this.$refs.promptInput.disabled = true;
					this.$refs.promptInput.placeholder = 'Analyzing image... Please wait...';
				}

				fetch('/dashboard/user/image-to-prompt', {
					method: 'POST',
					headers: {
						'X-CSRF-TOKEN': '{{ csrf_token() }}'
					},
					body: formData
				})
					.then(response => response.json())
					.then(data => {
						if (data.status === 'success') {
							this.prompt = data.prompt;
						} else {
							toastr.error(data.prompt);
						}
					})
					.catch(error => {
						console.log('Error:', error);
					})
					.finally(() => {
						if ( this.$refs.promptInput ) {
							this.$refs.promptInput.disabled = false;
							this.$refs.promptInput.placeholder = 'Describe your idea or select a pre-defined prompt';
						}
					});
			}

		},

		resetUploadedImageInput() {
			this.zoomLevel = 1;

			this.clearImageInputs();

			if (this.selectedTool === 'sketch_to_image') {
				this.makeCanvasEditable({ width: 1024, height: 1024 });
			}
		},
		clearImageInputs() {
			this.lastUploadingImages = [];
			this.uploadingImages = [];
			this.editingImage = {};

			this.$refs.editorFileInput.value = '';
			this.$refs.uploadedImageInput.value = '';
		},
		downloadImage(imagePath, imageName) {
			const link = document.createElement('a');
			link.href = imagePath;
			link.download = imageName ? imageName.replaceAll(' ', '-') : 'image';
			link.click();

			link.remove();
		},

		getMaxZoom() {
			/** @type {HTMLElement} */
			const canvas = this.$refs.editorCanvas;
			const canvasWidth = canvas.offsetWidth;
			const canvasStyles = window.getComputedStyle(canvas);
			const canvasMargin = parseFloat(canvasStyles.marginLeft) + parseFloat(canvasStyles.marginRight) - 20;

			return 1 + (canvasMargin / canvasWidth);
		},
		setZoomLevel(level) {
			const maxZoom = this.getMaxZoom();

			this.zoomLevel = Math.max(0.1, Math.min(maxZoom, level));
			this.reachedMinZoom = this.zoomLevel === 0.1;
			this.reachedMaxZoom = this.zoomLevel >= maxZoom;
		},
		zoomIn() {
			this.setZoomLevel(this.zoomLevel + 0.1);
		},
		zoomOut() {
			this.setZoomLevel(this.zoomLevel - 0.1);
		},
		fitToScreen() {
			const maxZoom = this.getMaxZoom();

			this.setZoomLevel(maxZoom);
		},
		onZoomLevelChange() {
			/** @type {HTMLElement} */
			const canvas = this.$refs.editorCanvas;

			canvas.style.setProperty('--zoom-level', this.zoomLevel.toFixed(3));

			canvas.style.setProperty('--zoom-offset', '0px');

			const canvasStyles = window.getComputedStyle(canvas);
			const headerHeight = parseFloat(canvasStyles.getPropertyValue('--header-h'));
			const canvasRect = canvas.getBoundingClientRect();
			const top = canvasRect.top + this.$refs.editorCanvasWrap.scrollTop;

			if (top < headerHeight) {
				canvas.style.setProperty('--zoom-offset', `${(top >= 0 ? headerHeight - top : headerHeight + Math.abs(top)).toFixed(0)}px`);
			}
		},

		submitEditorForm(event) {
			this.busy = true;

			const formData = new FormData(event.target);

			fetch(event.target.action, {
				method: 'POST',
				body: formData,
				headers: {
					'Accept': 'application/json'
				}
			})
				.then(response => {
					if (!response.ok) {
						return response.json().then(errorData => {
							throw new Error(errorData.message || 'An unknown error occurred');
						});
					}
					return response.json();
				})
				.then(data => {
					if (data.type === 'error') {
						toastr.error(data.message);
						return;
					}

					if (data?.data?.status === 'CREATED' || data?.data?.status === 'IN_PROGRESS') {
						this.task = true;

						this.getStatus(data.data);
					} else {
						this.editingImage = data.data;

						this.newItems.push(data.data);

						this.showNotif = true;

						this.uploadingImages = [];

						if (this.selectedTool === 'sketch_to_image') {
							this.switchToolsCat({ toolKey: this.primaryToolKeys[0] });
							this.selectedTool = this.primaryToolKeys[0];
						}

						const notifTimeout = setTimeout(() => {
							this.showNotif = false;
							clearTimeout(notifTimeout);
						}, 3000);
					}


				})
				.catch(error => {
					console.log(error);
					toastr.error(error?.message || error);
				})
				.finally(() => {
					if (!this.task) {
						this.busy = false;
					}
				});
		},

		async getStatus(data = {}) {

			this.busy  = true;

			fetch('/dashboard/user/advanced-image/editor/' + data.id + '/status', {
				method: 'GET',
				headers: {
					'Accept': 'application/json'
				}
			}).then(response => {
				if (!response.ok) {
					return response.json().then(errorData => {
						throw new Error(errorData.message || 'An unknown error occurred');
					});
				}
				return response.json();
			}).then(data => {
				if (data.status === 'error') {
					throw new Error(data.message);
				}

				if (data.data.status === 'COMPLETED') {
					this.task = false;

					this.editingImage = data.data;

					this.newItems.push(data.data);

					this.showNotif = true;

					const notifTimeout = setTimeout(() => {
						this.showNotif = false;
						clearTimeout(notifTimeout);
					}, 3000);

					this.busy = false;
				} else {
					setTimeout(() => {
						this.getStatus(data.data);
					}, 1000);
				}
			}).catch(error => {
				toastr.error(error?.message || error);
			});
		},

		/**
		 *
		 * @param {{width: number; height: number}} dimensions
		 */
		async makeCanvasEditable(dimensions = {}) {
			this.editingImageDimensions = {
				width: dimensions.width || this.$refs.editorImagePreview.naturalWidth,
				height: dimensions.height || this.$refs.editorImagePreview.naturalHeight
			};

			await this.$nextTick();

			const canvas = this.$refs.editorMaskCanvas;

			this.canvasCtx?.reset();

			this.canvasCtx = canvas.getContext('2d');

			this.canvasCtx.fillStyle = 'black';
			this.canvasCtx.fillRect(0, 0, canvas.width, canvas.height);
		},
		startPainting(event) {
			this.painting = true;
			this.painted = false;
			this.paint(event);
		},
		stopPainting() {
			if (!this.painted) return;

			this.painting = false;
			this.canvasCtx.beginPath();

			const canvas = this.$refs.editorMaskCanvas;
			const tempCanvas = document.createElement('canvas');
			tempCanvas.width = this.editingImageDimensions.width;
			tempCanvas.height = this.editingImageDimensions.height;
			const tempCtx = tempCanvas.getContext('2d');
			tempCtx.drawImage(canvas, 0, 0, tempCanvas.width, tempCanvas.height);

			tempCanvas.toBlob(blob => {
				const file = new File([ blob ], 'mask.png', { type: 'image/png' });
				const dataTransfer = new DataTransfer();
				dataTransfer.items.add(file);
				this.$refs[this.selectedTool === 'sketch_to_image' ? 'sketchFileInput' : 'maskFileInput'].files = dataTransfer.files;

				tempCanvas.remove();
			}, 'image/png');

			this.painted = false;
		},
		paint(event) {
			if (!this.painting) return;

			const canvas = this.$refs.editorMaskCanvas;
			const canvasRect = canvas.getBoundingClientRect();
			const x = (event.clientX - canvasRect.left) * (canvas.width / canvasRect.width);
			const y = (event.clientY - canvasRect.top) * (canvas.height / canvasRect.height);

			this.canvasCtx.lineWidth = this.brushSize;
			this.canvasCtx.opacityTo = 7;
			this.canvasCtx.lineCap = 'round';
			this.canvasCtx.strokeStyle = 'yellow';

			this.canvasCtx.lineTo(x, y);
			this.canvasCtx.stroke();
			this.canvasCtx.beginPath();
			this.canvasCtx.moveTo(x, y);

			this.painted = true;
		},
		setBrushSize(size) {
			if (size === '-') {
				size = Math.max(this.brushSize - 10, 10);
			} else if (size === '+') {
				size = Math.min(this.brushSize + 10, 100);
			}

			this.brushSize = parseInt(size, 10);
		},

		togglePromptLibraryShow() {
			this.promptLibraryShow = !this.promptLibraryShow;
		},
		changePromptFilter(filter) {
			filter !== this.promptFilter && (this.promptFilter = filter);
		},
		setSearchPromptStr(str) {
			this.searchPromptStr = str.trim().toLowerCase();
		},
		setPrompt(prompt) {
			this.prompt = prompt;
		},
		focusOnPrompt() {
			this.$nextTick(() => this.$refs.promptInput.focus());
		},

		onCreativeSuiteStageInitiated(initiated) {
			if ( !initiated ) return;

			_.defer(() => {
				// this.sidebarCollapsed = true;
				this.toolbarCollapsed = true;
			});
		}
	});
};
