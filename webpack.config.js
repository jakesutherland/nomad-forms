'use strict';

const autoprefixer = require('autoprefixer');
const browserlist = require('@wordpress/browserslist-config');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const path = require('path');
const glob = require('glob');

module.exports = function (env, options) {

	const entry = {
		"nomad-forms":  glob.sync('./src/assets/scss/nomad-forms.scss'),
		"nomad-forms-theme":  glob.sync('./src/assets/scss/nomad-forms-theme.scss'),
	};

	const paths = {
		css: './dist/css/',
	};

	const loaders = {
		css: {
			loader: 'css-loader',
			options: {
				sourceMap: true,
			},
		},
		postCss: {
			loader: 'postcss-loader',
			options: {
				plugins: [
					autoprefixer({
						browserlist,
						flexbox: 'no-2009',
					}),
				],
				sourceMap: true,
			},
		},
		sass: {
			loader: 'sass-loader',
			options: {
				sourceMap: true,
			},
		},
	};

	const config = {
		entry,
		output: {
			path: path.join(__dirname, '/'),
		},
		module: {
			rules: [
				{
					test: /\.css$/,
					use: [
						MiniCssExtractPlugin.loader,
						loaders.css,
						loaders.postCss,
					],
					exclude: /node_modules/,
				},
				{
					test: /\.scss$/,
					use: [
						MiniCssExtractPlugin.loader,
						loaders.css,
						loaders.postCss,
						loaders.sass,
					],
					exclude: /node_modules/,
				},
			],
		},
		plugins: [
			new MiniCssExtractPlugin({
				filename: `${paths.css}[name].min.css`,
			}),
			function (compiler) {
				compiler.hooks.emit.tap('RemoveEmptyJsFiles', function (compilation) {
					compilation.chunks.forEach(chunk => {
						if (!chunk.entryModule._identifier.includes('.js')) {
							chunk.files.forEach(file => {
								if (file.includes('.js')) {
									delete compilation.assets[file];
								}
							});
						}
					});
				});
			},
		],
		devtool: 'source-map',
	};

	return config;

};
