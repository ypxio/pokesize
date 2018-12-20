<template>
	<div class="container-fluid d-flex flex-column h-100">
		<a class="github-link" data-toggle="modal" data-target="#informationModal">
      <p class="nes-balloon from-right">Click Me <br> for information!</p>
      <!-- <i class="nes-octocat"></i> -->
    </a>

		<header class="row text-center mt-5">
			<div class="col-12">
				<h1>Pokemon Size Comparison</h1>
				<!-- <small>inspired from <a target="_blank" href="https://web.facebook.com/BlackOkumura/videos/1265385833603495/">this Facebook post</a></small> -->
				<br>
				<small v-if="!loading">
					<button @click="loadMore" v-if="loadedPokemonCount%limit === 0 && loadedPokemonCount != 0" class="nes-btn is-error">
						Load more {{ limit }} pokemon
					</button>
				</small>
			</div>
		</header>

		<div style="position: absolute; bottom: 0; width: 50% ash" class="mb-5 ml-5 text-center">
			<div class="row d-flex align-items-end">
				<div class="col-1">
					<img src="/images/ash.png" style="height: 160px" />
					<h6 class="mt-3">Ash</h6>
					<small>(1.6m)</small>
				</div>
				<div class="col-11 d-flex justify-content-start" style="height: 300px">
					<div class="message -left">
						<div class="nes-balloon from-left">
							<p>{{ loading ? (loadedPokemonCount >= limit ? 'Summoning more pokemon...' : 'Summoning all pokemon...') : loadedPokemonCount + ' Pokemon spawned!'}}</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row flex-nowrap mt-auto pb-5 pokemon-list" style="padding-left: 50%">
			<!-- <img src="/images/ash.png" alt="" /> -->

			<div v-for="dt in data" :key="dt.url" style="width: auto" class="d-flex align-items-end flex-column pokemon">
				<a href="#" @click="renderDetail(dt)" class="mt-auto mr-5 text-center">
					<img v-bind:src="dt.image" v-bind:style="{ height: (dt.detail.height * 10) + 'px' }" />
					<h6 class="name mt-3">{{ dt.name }}</h6>
					<small>
						({{ dt.detail.height/10}}m)
						<!-- <br> -->
						<!-- <span v-if="dt.detail.id === 95">(!)Scaled 1/5</span>	 -->
					</small>
				</a>
			</div>
		</div>

		<!-- Modal -->
		<div class="modal fade" id="informationModal">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content nes-container is-rounded">
					<div class="modal-header border-0">
						<h5 class="modal-title" id="exampleModalLabel">Pokesize Information</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<ul>
							<li>This project was built on Laravel API with Vue</li>
							<li>This project was inspired by <a target="_blank" href="https://web.facebook.com/BlackOkumura/videos/1265385833603495/">this Facebook post</a> when I scroll my facebook timeline last week :))</li>
							<li>Since original PokeAPI having problems on CORS, this project using mirrored verison of PokeAPI <a href="pokeapi.salestock.net/api/v2/" target="_blank">(http:pokeapi.salestock.net)</a></li>
							<li>Thanks to <a href="https://www.npmjs.com/package/jimp">Jimp</a>. this library is very useful to crop empty space from pokemon image sprites resulted from PokeAPI, because it has different scaling with each other pokemon until it cropped</li>
							<li>Github Repo: <a href="https://github.com/ypxio/pokesize">https://github.com/ypxio/pokesize</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div v-if="showModal">
			<transition name="modal">
				<div class="modal-mask">
					<div class="modal-wrapper">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content nes-container is-rounded">
								<div class="modal-header border-0">
									<h5 class="modal-title">POKEMON INFO</h5>
									<button type="button" class="close" @click="showModal = false" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class="row d-flex">
										<div class="col-4">
											<div class="nes-container with-title is-centered">
												<p class="title">{{ selectedPokemon ? selectedPokemon.name : 'Loading Name...' }}</p>
												<img :src="selectedPokemon.detail.sprites.front_default" :style="{ width: '160px' }" alt="" />
											</div>
											<div class="nes-container with-title">
												<p class="title">Stats</p>
												<table class="nes-table w-100">
													<tbody>
														<tr v-for="stat in selectedPokemon.detail.stats" :key="stat">
															<td>{{ stat.stat.name }}</td>
															<td></td>
															<td class="text-right">{{ stat.base_stat }}</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="col-8">
											<div class="nes-container with-title">
												<p class="title">Info</p>
												<ul>
													<li>Name: {{ capitalizeFirstLetter(selectedPokemon.name) }}</li>
													<li>Height: {{ selectedPokemon.detail.height / 10 }} m</li>
													<li>Weight: {{ selectedPokemon.detail.weight / 10 }} kg</li>
												</ul>
											</div>
											<div class="nes-container with-title">
												<p class="title">Abilities</p>
												<div>
													<button 
														v-for="ability in selectedPokemon.detail.abilities" 
														:key="ability.ability.url" 
														type="button" 
														class="nes-btn is-success mr-2"
														disabled
													>
														{{ ability.ability.name }}
													</button>
												</div>
											</div>
											<div class="nes-container with-title">
												<p class="title">Types</p>
												<div>
													<button 
														v-for="type in selectedPokemon.detail.types" 
														:key="type.type.url" 
														type="button" 
														class="nes-btn is-warning mr-2"
														disable
													>
														{{ type.type.name }}
													</button>
												</div>
											</div>
											<div class="nes-container with-title">
												<p class="title">Evolution Tree</p>
												Evolution Tree coming soon. stay tuned!
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</transition>
		</div>

	</div>
</template>

<script>

var Jimp = require('jimp');

export default {

	mounted() {
		console.log('Component mounted.')
	},
	created() {
		this.render()
	},
	data() {
		return {
			title: 'Pokepedia',
			description: 'This is Pokemon Encyclopedia',
			data: [],
			loading: false,
			loadedPokemonCount: 0,
			selectedPokemon: {},
			limit: 10,
			offset: 0,
			showModal: false
		}
	},
	methods: {
		render() {
			this.loading = true
			this.fetchPokemon(this.offset)
		},
		fetchPokemon (offset) {
			window.axios.get(`/api/pokemon/?limit=${this.limit}&offset=${offset}`).then(response => {
				let pokemons = response.data.results
				let promises = []
				pokemons.map((pokemon, index) => promises.push(new Promise(resolve => {
					let pokeId = pokemon.url.split('/')[pokemon.url.split('/').length - 2]
					this.getDetail(pokeId).then(poke => {
						this.loading = false
						pokemon.detail = poke.data
						this.trimImage(poke.data.sprites.front_default).then(img => {
							pokemon.image = img
							this.data.push(pokemon)
						})
						console.log(pokemon)
						console.log(pokemon.name + ' has been spawned!')
					})
				})))
      })
		},
		getDetail (id) {
      return new Promise((resolve, reject) => {
        window.axios.get(`/api/pokemon/${id}`).then(pokemon => {
					resolve(pokemon)
					this.loadedPokemonCount++
        })
      })
		},
		loadMore () {
			// alert('this function will be coming very soon!')
			this.loading = true
			this.fetchPokemon(this.loadedPokemonCount)
		},
		renderDetail (pokemon) {
			this.showModal = true
			this.selectedPokemon = pokemon 
			console.log(this.selectedPokemon)
		},
		trimImage(imageURL) {
			return Jimp.read(imageURL).then(image => {
				return image.autocrop().getBase64Async(Jimp.MIME_PNG)
			})
		},
		capitalizeFirstLetter(string) {
			return string.charAt(0).toUpperCase() + string.slice(1);
		}
	}

}
</script>

<style>

.row {
  overflow-x: auto;
  white-space: nowrap;
}
.row > .col-md-2 {
  display: inline-block;
  float: none;
}
.card {
	background-color: transparent !important;
	border: none;
}
.pokemon a {
	color: black !important;
}
.pokemon a:hover {
	text-decoration: none !important;
}
.pokemon a:hover img {
	margin-bottom: 8px;
	/* border-bottom: 1px solid black; */
}
.pokemon .name {
	text-transform: capitalize;
}
header.row {
	overflow-x: visible !important;
	/* z-index: 0 !important; */
}
.pokemon {
	/* z-index: 0 !important; */
}
.ash img {
	/* z-index: 100; */
}
.nes-container {
	margin: initial !important;
}
header {
	/* z-index: 9999999 !important; */
}
.modal-lg {
	width: 95% !important;
	max-width: 95% !important;
}
.modal-body {
	z-index: 1
}
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.github-link {
	position: fixed;
	top: 10px;
	right: 10px;
	z-index: 999;
	display: flex;
	height: 100px;
	color: #333;
	text-decoration: none;
}
.github-link:hover {
  text-decoration: none;
}
.github-link > p.nes-balloon {
  align-self: flex-start;
  padding: 0.2rem 0.5rem;
  font-size: 0.8rem;
  color: #333;
}
.github-link > i.nes-octocat {
  align-self: flex-end;
}
</style>
