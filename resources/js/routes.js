import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
	routes: [
		{
			path: '/scraping',
			name: 'scraping',
			component: require('./components/Visualizer')
		}
	],
	mode: 'history',
})
