const webpack = require( 'webpack' );
const NODE_ENV = process.env.NODE_ENV || 'development';
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );

module.exports = {
	mode: NODE_ENV,
	entry: './src/index.js',
	output: {
		path: __dirname,
		filename: './build/block.js'
	},
	module: {
		rules: [
			{
				test: /\.js?$/,
				use: [ {
					loader: 'babel-loader',
					options: {
						presets: [ '@babel/preset-env' ],
						plugins: [
							'@babel/plugin-transform-async-to-generator',
							'@babel/plugin-transform-object-rest-spread',
							[
								'@babel/plugin-transform-react-jsx', {
									'pragma': 'wp.element.createElement'
								}
							]
						]
					}
				} ],
				exclude: /node_modules/
			},
			{
				test: /\.(css|scss)$/,
				use: [ 
					MiniCssExtractPlugin.loader,
					'css-loader',
					{
						loader: 'postcss-loader',
						options: {
							postcssOptions: {
								plugins: [
									require( 'autoprefixer' )
								]
							}
						}
					},
					{
						loader: 'sass-loader',
						options: {
							sassOptions: {
								outputStyle:
									'production' === process.env.NODE_ENV ? 'compressed' : 'nested'
							}
						}
					} 
				]
			}
		]
	},
	plugins: [
		new webpack.DefinePlugin({
			'process.env.NODE_ENV': JSON.stringify( NODE_ENV )
		}),
		new MiniCssExtractPlugin({
			filename: './build/block.css'
		})
	],
	resolve: {
		extensions: [ '.js', '.jsx' ]
	}
};
