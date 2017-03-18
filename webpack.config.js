const webpack = require('webpack');
const path = require('path');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

module.exports = {
    devtool: 'eval', //'source-map',
    entry: [
        './resources/app.js'
    ],
    output: {
        path: path.resolve(__dirname, "public/js"),
        publicPath: "/js/",
        filename: "main.min.js"
    },
    plugins: [
        new webpack.DefinePlugin({
          'process.env': {
            'NODE_ENV': JSON.stringify('production')
          }
        }),
        new webpack.optimize.UglifyJsPlugin({
            sourceMap: true
        }),
        new ExtractTextPlugin('../css/main.min.css'),
        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery",
            "window.jQuery": "jquery",
            "React": "react"
        })
    ],
    resolve: {
        alias: {
            foundation$: 'foundation-sites/js/foundation.core'
        }
    },
    module: {
        rules: [
            {
                test: /foundation\.core/,
                use: [
                    {
                        loader: "babel-loader",
                        options: {
                            presets: ['es2015']
                        }
                    },
                    {
                        loader: "exports-loader",
                        options: {
                            foundation: "jQuery.fn.foundation"
                        }
                    }
                ]
            },
            {
                test: /foundation-sites\/js/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['es2015']
                    }
                }
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: "babel-loader",
                    options: {
                        presets: ['es2015']
                    }
                }
            },
            {
                test: /\.scss$/,
                use: ExtractTextPlugin.extract({
                    fallback: 'style-loader',
                    use: 'css-loader?sourceMap!sass-loader?sourceMap'
                })
            }
        ]
    },
};
